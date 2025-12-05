<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use App\Models\MentorCommissionPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();
        $perPage = 10;
        $start = request('date_start');
        $end = request('date_end');
        $base = TransactionDetail::whereHas('transaction', function($q) use($start, $end){
                $q->where('payment_status','success');
                if ($start) { $q->where('transaction_time', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); }
                if ($end) { $q->where('transaction_time', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); }
            })
            ->where(function($q) use($mentorId){
                $q->whereHas('course', function($qc) use($mentorId){ $qc->where('author_id', $mentorId); })
                  ->orWhereHas('ebook', function($qe) use($mentorId){ $qe->where('author_id', $mentorId); });
            });

        $details = (clone $base)->with([
                'transaction' => function($q){ $q->select('id','transaction_time','payment_status'); },
                'course' => function($q){ $q->select('id','author_id','title'); },
                'ebook' => function($q){ $q->select('id','author_id','title'); },
            ])
            ->orderByDesc('id')
            ->simplePaginate($perPage)
            ->withQueryString();

        $totalItems = (clone $base)->count();
        $totalMentor = (float) (clone $base)->sum('mentor_earning');
        $totalAdmin = (float) (clone $base)->sum('admin_commission');
        if ($totalMentor <= 0.0 && $totalAdmin <= 0.0) {
            (clone $base)->with(['transaction','course','ebook'])
                ->orderBy('id')
                ->chunkById(1000, function($rows) use (&$totalMentor, &$totalAdmin) {
                    foreach ($rows as $d) {
                        $totalMentor += (float)$d->mentor_earning;
                        $totalAdmin += (float)$d->admin_commission;
                    }
                }, $column = 'id');
        }

        $paid = (float) MentorCommissionPayout::where('user_id', $mentorId)->where('status','approved')->sum('amount');
        $pending = (float) MentorCommissionPayout::where('user_id', $mentorId)->where('status','pending')->sum('amount');
        $available = max(0.0, $totalMentor - $paid - $pending);

        $payouts = MentorCommissionPayout::with(['bankAccount'])
            ->where('user_id', $mentorId)
            ->orderByDesc('requested_at')
            ->simplePaginate(10, ['*'], 'history_page')
            ->withQueryString();

        $agg = \App\Models\TransactionDetail::join('transactions','transaction_details.transaction_id','=','transactions.id')
            ->where('transactions.payment_status','success')
            ->where(function($q) use($mentorId){
                $q->whereHas('course', function($qc) use($mentorId){ $qc->where('author_id', $mentorId); })
                  ->orWhereHas('ebook', function($qe) use($mentorId){ $qe->where('author_id', $mentorId); });
            })
            ->when($start, function($q) use($start){ $q->where('transactions.transaction_time', '>=', \Illuminate\Support\Carbon::parse($start)->startOfDay()); })
            ->when($end, function($q) use($end){ $q->where('transactions.transaction_time', '<=', \Illuminate\Support\Carbon::parse($end)->endOfDay()); })
            ->selectRaw('DATE(transactions.transaction_time) as d, SUM(mentor_earning) as mentor, SUM(admin_commission) as admin, COUNT(*) as cnt')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $chart = [
            'labels' => $agg->map(function($r){ return (string) \Illuminate\Support\Carbon::parse($r->d)->format('d'); })->values(),
            'bars' => $agg->map(function($r){ return (int) $r->cnt; })->values(),
            'line' => $agg->map(function($r){ return (float) ($r->mentor + $r->admin); })->values(),
        ];

        return view('pages.mentor.commissions.index', compact('details','totalItems','totalMentor','totalAdmin','paid','pending','available','payouts','chart'));
    }

    public function requestPayout(Request $request)
    {
        $mentorId = Auth::id();
        $defaultAccount = \App\Models\MentorBankAccount::where('user_id', $mentorId)->where('is_default', true)->first();
        if (!$defaultAccount) {
            return back()->with('success','Silakan set Rekening Bank default di Settings sebelum mengajukan pencairan.');
        }
        $totalMentor = (float) TransactionDetail::whereHas('transaction', function($q){ $q->where('payment_status','success'); })
            ->where(function($q) use($mentorId){
                $q->whereHas('course', function($qc) use($mentorId){ $qc->where('author_id', $mentorId); })
                  ->orWhereHas('ebook', function($qe) use($mentorId){ $qe->where('author_id', $mentorId); });
            })
            ->sum('mentor_earning');

        $alreadyPaid = (float) MentorCommissionPayout::where('user_id', $mentorId)->where('status','approved')->sum('amount');
        $alreadyPending = (float) MentorCommissionPayout::where('user_id', $mentorId)->where('status','pending')->sum('amount');
        $amount = max(0.0, $totalMentor - $alreadyPaid - $alreadyPending);

        if ($amount <= 0.0) {
            return back()->with('success','Tidak ada komisi yang tersedia untuk dicairkan.');
        }

        MentorCommissionPayout::create([
            'user_id' => $mentorId,
            'mentor_bank_account_id' => $defaultAccount->id,
            'amount' => $amount,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $admins = \App\Models\User::where('role','admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'recipient_id' => $admin->id,
                'message' => 'Permintaan pencairan komisi dari mentor #'.$mentorId,
                'link_url' => route('admin.commissions.index', ['date_start' => $request->input('date_start'), 'date_end' => $request->input('date_end')]),
                'created_at' => now(),
            ]);
        }

        return back()->with('success','Permintaan pencairan komisi dikirim ke admin.');
    }
}
