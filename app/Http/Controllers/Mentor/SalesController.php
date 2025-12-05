<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();
        $perPage = 10;

        $base = TransactionDetail::whereHas('transaction', function($q){
                $q->where('payment_status','success');
            })
            ->where(function($q) use($mentorId){
                $q->whereHas('course', function($qc) use($mentorId){ $qc->where('author_id', $mentorId); })
                  ->orWhereHas('ebook', function($qe) use($mentorId){ $qe->where('author_id', $mentorId); });
            });

        $details = (clone $base)->with([
                'transaction' => function($q){ $q->select('id','transaction_time','payment_status'); },
                'course' => function($q){ $q->select('id','author_id','title','price'); },
                'ebook' => function($q){ $q->select('id','author_id','title','price'); },
            ])
            ->orderByDesc('id')
            ->simplePaginate($perPage)
            ->withQueryString();

        $totalItems = (clone $base)->count();
        $totalGross = (float) (clone $base)->sum('price');
        $totalMentor = (float) ((clone $base)->sum('mentor_earning') ?? 0);
        $totalAdmin = (float) ((clone $base)->sum('admin_commission') ?? 0);
        if ($totalMentor <= 0.0 && $totalAdmin <= 0.0) {
            $totalMentor = 0.0; $totalAdmin = 0.0;
            (clone $base)->with(['transaction','course','ebook'])
                ->orderBy('id')
                ->chunkById(1000, function($rows) use (&$totalMentor, &$totalAdmin) {
                    foreach ($rows as $d) {
                        $totalMentor += (float)$d->mentor_earning;
                        $totalAdmin += (float)$d->admin_commission;
                    }
                }, $column = 'id');
        }
        $totalEffective = $totalMentor + $totalAdmin;

        return view('pages.mentor.sales.index', compact('details','totalItems','totalGross','totalEffective','totalMentor'));
    }
}
