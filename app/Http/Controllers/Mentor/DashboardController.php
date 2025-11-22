<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Enrollment;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();
        $mentorShare = 0.8;
        
        // Statistik utama
        $totalCourses = Course::where('author_id', $mentorId)->count();
        $totalEbooks = Ebook::where('author_id', $mentorId)->count();
        $totalEnrollments = Enrollment::whereHas('course', function($query) use ($mentorId) {
            $query->where('author_id', $mentorId);
        })->count();
        
        $courseRevenue = TransactionDetail::where('product_type', 'course')
            ->whereHas('course', function($q) use ($mentorId) {
                $q->where('author_id', $mentorId);
            })
            ->whereHas('transaction', function($query) {
                $query->where('payment_status', 'success');
            })
            ->sum('price');

        $ebookRevenue = TransactionDetail::where('product_type', 'ebook')
            ->whereHas('ebook', function($q) use ($mentorId) {
                $q->where('author_id', $mentorId);
            })
            ->whereHas('transaction', function($query) {
                $query->where('payment_status', 'success');
            })
            ->sum('price');

        $courseSoldCount = TransactionDetail::where('product_type', 'course')
            ->whereHas('course', function($q) use ($mentorId) {
                $q->where('author_id', $mentorId);
            })
            ->whereHas('transaction', function($query) {
                $query->where('payment_status', 'success');
            })
            ->count();

        $ebookSoldCount = TransactionDetail::where('product_type', 'ebook')
            ->whereHas('ebook', function($q) use ($mentorId) {
                $q->where('author_id', $mentorId);
            })
            ->whereHas('transaction', function($query) {
                $query->where('payment_status', 'success');
            })
            ->count();

        $totalRevenue = $courseRevenue + $ebookRevenue;

        $courseEarnings = $courseRevenue * $mentorShare;
        $courseCommission = $courseRevenue - $courseEarnings;
        $ebookEarnings = $ebookRevenue * $mentorShare;
        $ebookCommission = $ebookRevenue - $ebookEarnings;
        $totalEarnings = $totalRevenue * $mentorShare;
        $totalCommission = $totalRevenue - $totalEarnings;

        // Kursus populer (berdasarkan jumlah enrollments)
        $popularCourses = Course::where('author_id', $mentorId)
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        // Kursus dengan progress kuis terbaru
        $recentQuizAttempts = UserQuizAttempt::whereHas('quiz.lesson.module.course', function($query) use ($mentorId) {
            $query->where('author_id', $mentorId);
        })
        ->with(['quiz.lesson.module.course', 'user'])
        ->latest()
        ->take(10)
        ->get();

        // Transaksi terbaru untuk konten mentor
        $recentTransactions = TransactionDetail::where(function($query) use ($mentorId) {
            $query->where('product_type', 'course')
                  ->whereHas('course', function($q) use ($mentorId) {
                      $q->where('author_id', $mentorId);
                  });
        })->orWhere(function($query) use ($mentorId) {
            $query->where('product_type', 'ebook')
                  ->whereHas('ebook', function($q) use ($mentorId) {
                      $q->where('author_id', $mentorId);
                  });
        })->whereHas('transaction', function($query) {
            $query->where('payment_status', 'success');
        })
        ->with(['transaction.user', 'course', 'ebook'])
        ->orderByDesc(Transaction::select('transaction_time')
            ->whereColumn('transactions.id', 'transaction_details.transaction_id'))
        ->take(10)
        ->get();

        // Status kursus
        $courseStats = [
            'draft' => Course::where('author_id', $mentorId)->where('status', 'draft')->count(),
            'published' => Course::where('author_id', $mentorId)->where('status', 'published')->count(),
            'archived' => Course::where('author_id', $mentorId)->where('status', 'archived')->count(),
        ];

        // Status ebook
        $ebookStats = [
            'draft' => Ebook::where('author_id', $mentorId)->where('status', 'draft')->count(),
            'published' => Ebook::where('author_id', $mentorId)->where('status', 'published')->count(),
            'archived' => Ebook::where('author_id', $mentorId)->where('status', 'archived')->count(),
        ];

        $courseActive = $courseStats['published'];
        $courseNonactive = $totalCourses - $courseActive;
        $ebookActive = $ebookStats['published'];
        $ebookNonactive = $totalEbooks - $ebookActive;

        $courseStudents = Enrollment::whereHas('course', function($query) use ($mentorId) {
            $query->where('author_id', $mentorId);
        })->count();

        $ebookStudents = \App\Models\UserEbookLibrary::whereHas('ebook', function($query) use ($mentorId) {
            $query->where('author_id', $mentorId);
        })->count();

        $stats = [
            'overall' => [
                'total_revenue' => $totalRevenue,
                'total_earnings' => $totalEarnings,
                'total_commission' => $totalCommission,
                'total_students' => $courseStudents + $ebookStudents,
            ],
            'course' => [
                'sold_count' => $courseSoldCount,
                'revenue' => $courseRevenue,
                'earnings' => $courseEarnings,
                'commission' => $courseCommission,
            ],
            'ebook' => [
                'sold_count' => $ebookSoldCount,
                'revenue' => $ebookRevenue,
                'earnings' => $ebookEarnings,
                'commission' => $ebookCommission,
            ],
            'course_stats' => $courseStats,
            'ebook_stats' => $ebookStats,
        ];

        return view('pages.mentor.dashboard', compact('stats', 'popularCourses', 'recentQuizAttempts', 'recentTransactions'));
    }
}
