<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();
        $start = request('date_start');
        $end = request('date_end');
        if (!$start || !$end) {
            $end = Carbon::now()->toDateString();
            $start = Carbon::now()->subDays(6)->toDateString();
        }

        $details = TransactionDetail::with(['transaction','course','ebook'])
            ->whereHas('transaction', function($q) use ($start, $end) {
                $q->where('payment_status','success')
                  ->whereBetween('transaction_time', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
            })
            ->where(function($q) use ($mentorId){
                $q->whereHas('course', function($qc) use ($mentorId){ $qc->where('author_id', $mentorId); })
                  ->orWhereHas('ebook', function($qe) use ($mentorId){ $qe->where('author_id', $mentorId); });
            })
            ->orderBy('id','asc')
            ->get();

        $labels = [];
        $bars = [];
        $line = [];
        $map = [];
        $period = Carbon::parse($start)->daysUntil(Carbon::parse($end)->addDay());
        foreach ($period as $day) { $key = $day->format('Y-m-d'); $labels[] = $day->format('d'); $map[$key] = ['cnt'=>0,'amt'=>0.0]; }

        $totalSales = 0.0; $totalOrders = 0; $coursesSold = 0; $ebooksSold = 0;
        foreach ($details as $d) {
            $dateKey = optional($d->transaction->transaction_time)->format('Y-m-d');
            $amt = (float)$d->effective_price; // accessor
            $totalSales += $amt; $totalOrders += 1;
            if ($d->product_type === 'course') { $coursesSold += 1; } else { $ebooksSold += 1; }
            if (isset($map[$dateKey])) { $map[$dateKey]['cnt'] += 1; $map[$dateKey]['amt'] += $amt; }
        }
        foreach ($map as $v) { $bars[] = (int)$v['cnt']; $line[] = (float)$v['amt']; }

        return view('pages.mentor.analytics.index', compact('labels','bars','line','totalSales','totalOrders','coursesSold','ebooksSold','start','end'));
    }
}

