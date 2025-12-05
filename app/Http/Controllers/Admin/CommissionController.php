<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use App\Models\MentorCommissionPayout;
use App\Models\User;
use App\Models\MentorBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('date_start');
        $end = $request->query('date_end');

        $agg = TransactionDetail::join('transactions','transaction_details.transaction_id','=','transactions.id')
            ->leftJoin('courses','transaction_details.course_id','=','courses.id')
            ->leftJoin('ebooks','transaction_details.ebook_id','=','ebooks.id')
            ->where('transactions.payment_status','success')
            ->when($start, function($q) use($start){ $q->where('transactions.transaction_time', '>=', Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('transactions.transaction_time', '<=', Carbon::parse($end)->endOfDay()); })
            ->selectRaw('COALESCE(courses.author_id, ebooks.author_id) as mentor_id, SUM(transaction_details.mentor_earning) as total_mentor')
            ->groupBy('mentor_id')
            ->get();

        $mentorIds = $agg->pluck('mentor_id')->filter()->unique()->values();
        $users = User::whereIn('id', $mentorIds)->get()->keyBy('id');
        $banks = MentorBankAccount::whereIn('user_id', $mentorIds)->where('is_default', true)->get()->keyBy('user_id');
        $adminAccounts = \App\Models\AdminBankAccount::orderBy('bank_name')->get();

        $paidRows = MentorCommissionPayout::whereIn('user_id', $mentorIds)
            ->where('status','approved')
            ->when($start, function($q) use($start){ $q->where('processed_at', '>=', Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('processed_at', '<=', Carbon::parse($end)->endOfDay()); })
            ->selectRaw('user_id, SUM(amount) as amount')
            ->groupBy('user_id')
            ->get()->keyBy('user_id');

        $pendingRows = MentorCommissionPayout::whereIn('user_id', $mentorIds)
            ->where('status','pending')
            ->when($start, function($q) use($start){ $q->where('requested_at', '>=', Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('requested_at', '<=', Carbon::parse($end)->endOfDay()); })
            ->selectRaw('user_id, SUM(amount) as amount')
            ->groupBy('user_id')
            ->get()->keyBy('user_id');

        $rows = [];
        foreach ($agg as $a) {
            $uid = (int)$a->mentor_id;
            $total = (float)$a->total_mentor;
            if ($total <= 0.0) {
                $chunkBase = TransactionDetail::join('transactions','transaction_details.transaction_id','=','transactions.id')
                    ->leftJoin('courses','transaction_details.course_id','=','courses.id')
                    ->leftJoin('ebooks','transaction_details.ebook_id','=','ebooks.id')
                    ->where('transactions.payment_status','success')
                    ->when($start, function($q) use($start){ $q->where('transactions.transaction_time', '>=', Carbon::parse($start)->startOfDay()); })
                    ->when($end, function($q) use($end){ $q->where('transactions.transaction_time', '<=', Carbon::parse($end)->endOfDay()); })
                    ->where(function($q) use($uid){
                        $q->where('courses.author_id', $uid)->orWhere('ebooks.author_id', $uid);
                    })
                    ->select('transaction_details.id as td_id');
                $sum = 0.0;
                $chunkBase->orderBy('transaction_details.id')->chunkById(1000, function($rows) use (&$sum){
                    $details = TransactionDetail::with(['transaction','course','ebook'])->whereIn('id', $rows->pluck('td_id'))->get();
                    foreach ($details as $d) { $sum += (float)$d->mentor_earning; }
                }, 'transaction_details.id', 'td_id');
                $total = $sum;
            }

            $paid = (float)($paidRows[$uid]->amount ?? 0.0);
            $pending = (float)($pendingRows[$uid]->amount ?? 0.0);
            $available = max(0.0, $total - $paid - $pending);
            $rows[] = [
                'user' => $users[$uid] ?? null,
                'total' => $total,
                'approved' => $paid,
                'pending' => $pending,
                'available' => $available,
                'bank' => $banks[$uid] ?? null,
            ];
        }

        usort($rows, function($a,$b){ return ($b['available'] <=> $a['available']); });

        $totalApproved = array_sum(array_map(fn($r)=> (float)$r['approved'], $rows));
        $totalPending = array_sum(array_map(fn($r)=> (float)$r['pending'], $rows));
        $totalAvailable = array_sum(array_map(fn($r)=> (float)$r['available'], $rows));

        return view('pages.admin.commissions.index', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
            'totalApproved' => $totalApproved,
            'totalPending' => $totalPending,
            'totalAvailable' => $totalAvailable,
            'adminAccounts' => $adminAccounts,
        ]);
    }

    public function show(Request $request, User $mentor)
    {
        $start = $request->query('date_start');
        $end = $request->query('date_end');

        $base = TransactionDetail::with(['transaction','course','ebook'])
            ->whereHas('transaction', function($q) use($start,$end){
                $q->where('payment_status','success');
                if ($start) { $q->where('transaction_time', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); }
                if ($end) { $q->where('transaction_time', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); }
            })
            ->where(function($q) use($mentor){
                $q->whereHas('course', function($qc) use($mentor){ $qc->where('author_id', $mentor->id); })
                  ->orWhereHas('ebook', function($qe) use($mentor){ $qe->where('author_id', $mentor->id); });
            })
            ->orderByDesc('id');

        $details = $base->simplePaginate(10)->withQueryString();
        $baseMentor = clone $base;
        $baseAdmin = clone $base;
        $totalMentor = (float) $baseMentor->sum('mentor_earning');
        $totalAdmin = (float) $baseAdmin->sum('admin_commission');

        if ($totalMentor <= 0.0 && $totalAdmin <= 0.0) {
            $idsQuery = TransactionDetail::join('transactions','transaction_details.transaction_id','=','transactions.id')
                ->leftJoin('courses','transaction_details.course_id','=','courses.id')
                ->leftJoin('ebooks','transaction_details.ebook_id','=','ebooks.id')
                ->where('transactions.payment_status','success')
                ->when($start, function($q) use($start){ $q->where('transactions.transaction_time', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
                ->when($end, function($q) use($end){ $q->where('transactions.transaction_time', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
                ->where(function($q) use($mentor){
                    $q->where('courses.author_id', $mentor->id)->orWhere('ebooks.author_id', $mentor->id);
                })
                ->select('transaction_details.id as td_id')
                ->orderBy('transaction_details.id');

            $sumM = 0.0; $sumA = 0.0;
            $idsQuery->chunkById(1000, function($rows) use (&$sumM,&$sumA){
                $detailsChunk = TransactionDetail::with(['transaction','course','ebook'])
                    ->whereIn('id', $rows->pluck('td_id'))
                    ->get();
                foreach ($detailsChunk as $d) { $sumM += (float)$d->mentor_earning; $sumA += (float)$d->admin_commission; }
            }, 'transaction_details.id', 'td_id');
            $totalMentor = $sumM; $totalAdmin = $sumA;
        }

        $paid = (float) MentorCommissionPayout::where('user_id', $mentor->id)
            ->where('status','approved')
            ->when($start, function($q) use($start){ $q->where('processed_at', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('processed_at', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
            ->sum('amount');

        $pending = (float) MentorCommissionPayout::where('user_id', $mentor->id)
            ->where('status','pending')
            ->when($start, function($q) use($start){ $q->where('requested_at', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('requested_at', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
            ->sum('amount');

        $available = max(0.0, $totalMentor - $paid - $pending);

        return view('pages.admin.commissions.show', compact('mentor','details','start','end','totalMentor','totalAdmin','paid','pending','available'));
    }

    public function payout(Request $request, User $mentor)
    {
        $start = $request->input('date_start');
        $end = $request->input('date_end');
        $amount = (float) $request->input('amount');
        $adminBankId = (int) $request->input('admin_bank_account_id');

        $available = 0.0;
        $base = TransactionDetail::join('transactions','transaction_details.transaction_id','=','transactions.id')
            ->leftJoin('courses','transaction_details.course_id','=','courses.id')
            ->leftJoin('ebooks','transaction_details.ebook_id','=','ebooks.id')
            ->where('transactions.payment_status','success')
            ->when($start, function($q) use($start){ $q->where('transactions.transaction_time', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('transactions.transaction_time', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
            ->where(function($q) use($mentor){
                $q->where('courses.author_id', $mentor->id)->orWhere('ebooks.author_id', $mentor->id);
            });
        $totalMentor = (float) $base->sum('mentor_earning');
        $paid = (float) MentorCommissionPayout::where('user_id', $mentor->id)->where('status','approved')
            ->when($start, function($q) use($start){ $q->where('processed_at', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('processed_at', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
            ->sum('amount');
        $pending = (float) MentorCommissionPayout::where('user_id', $mentor->id)->where('status','pending')
            ->when($start, function($q) use($start){ $q->where('requested_at', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('requested_at', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
            ->sum('amount');
        $available = max(0.0, $totalMentor - $paid - $pending);

        if ($amount <= 0.0 || $amount > $available) {
            return back()->with('error','Nominal tidak valid atau melebihi komisi tersedia.');
        }

        $mentorDefaultBank = MentorBankAccount::where('user_id', $mentor->id)->where('is_default', true)->first();

        $fee = (float) (\App\Models\PlatformSetting::get('commission_admin_fee', '0') ?? '0');
        MentorCommissionPayout::create([
            'user_id' => $mentor->id,
            'mentor_bank_account_id' => $mentorDefaultBank?->id,
            'admin_bank_account_id' => $adminBankId ?: null,
            'amount' => $amount,
            'admin_fee' => $fee,
            'status' => 'approved',
            'requested_at' => now(),
            'processed_at' => now(),
        ]);

        \App\Models\Notification::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'recipient_id' => $mentor->id,
            'message' => 'Komisi Anda telah dicairkan sebesar Rp '.number_format($amount,0,',','.'),
            'link_url' => route('mentor.commissions.index'),
            'created_at' => now(),
        ]);

        return back()->with('success','Komisi dicairkan untuk mentor.');
    }
}
