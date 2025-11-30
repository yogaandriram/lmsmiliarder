<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\MentorVerificationController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\CourseVerificationController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AdminBankAccountController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Public\CourseController as PublicCourseController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Public course preview by slug
Route::get('/courses/{slug}', [PublicCourseController::class, 'show'])->name('public.courses.show');
// Public course preview by author + course slug
Route::get('/courses/{mentor}/{course}', [PublicCourseController::class, 'showByAuthorCourse'])->name('public.courses.show.by_author');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OTP verification routes
Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp/request', [OtpController::class, 'requestOtp'])->name('otp.request');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

// Forgot & Reset Password
Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.forgot');
Route::post('/password/email', [PasswordResetController::class, 'sendLink'])->name('password.email');
Route::get('/reset-password', [PasswordResetController::class, 'showReset'])->name('password.reset.form');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');

// Admin routes
Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profil admin
        Route::get('/profile', [AdminUserController::class, 'profile'])->name('profile');

        // Kategori & Tag
        Route::resource('categories', AdminCategoryController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('tags', AdminTagController::class)->only(['index','create','store','edit','update','destroy']);

        // Toggle aktif/non-aktif
        Route::patch('categories/{category}/toggle', [AdminCategoryController::class, 'toggle'])->name('categories.toggle');
        Route::patch('tags/{tag}/toggle', [AdminTagController::class, 'toggle'])->name('tags.toggle');

        // Verifikasi Mentor
        Route::get('mentor-verifications', [MentorVerificationController::class, 'index'])->name('mentor_verifications.index');
        Route::post('mentor-verifications/{verification}/approve', [MentorVerificationController::class, 'approve'])->name('mentor_verifications.approve');
        Route::post('mentor-verifications/{verification}/reject', [MentorVerificationController::class, 'reject'])->name('mentor_verifications.reject');

        // Verifikasi Kursus
        Route::get('course-verifications', [CourseVerificationController::class, 'index'])->name('course_verifications.index');
        Route::get('course-verifications/{course}', [CourseVerificationController::class, 'show'])->name('course_verifications.show');
        Route::get('course-verifications/{course}/lessons/{lesson}', [CourseVerificationController::class, 'showLesson'])->name('course_verifications.lessons.show');
        Route::post('course-verifications/{course}/approve', [CourseVerificationController::class, 'approve'])->name('course_verifications.approve');
        Route::post('course-verifications/{course}/reject', [CourseVerificationController::class, 'reject'])->name('course_verifications.reject');

        // Verifikasi E-book
        Route::get('ebook-verifications', [\App\Http\Controllers\Admin\EbookVerificationController::class, 'index'])->name('ebook_verifications.index');
        Route::get('ebook-verifications/{ebook}', [\App\Http\Controllers\Admin\EbookVerificationController::class, 'show'])->name('ebook_verifications.show');
        Route::post('ebook-verifications/{ebook}/approve', [\App\Http\Controllers\Admin\EbookVerificationController::class, 'approve'])->name('ebook_verifications.approve');
        Route::post('ebook-verifications/{ebook}/reject', [\App\Http\Controllers\Admin\EbookVerificationController::class, 'reject'])->name('ebook_verifications.reject');

        // Transaksi - verifikasi pembayaran
        Route::get('transactions/pending', [AdminTransactionController::class, 'pending'])->name('transactions.pending');
        Route::post('transactions/{transaction}/verify', [AdminTransactionController::class, 'verify'])->name('transactions.verify');

        // Pengumuman
        Route::resource('announcements', AnnouncementController::class)->only(['index','store','destroy']);

        // Diskusi
        Route::get('discussions', [\App\Http\Controllers\Admin\DiscussionController::class, 'index'])->name('discussions.index');
        Route::get('discussions/{group}/chat', [\App\Http\Controllers\Admin\DiscussionController::class, 'chat'])->name('discussions.chat');
        Route::post('discussions/{group}/chat', [\App\Http\Controllers\Admin\DiscussionController::class, 'postMessage'])->name('discussions.chat.post');
        Route::get('discussions/{group}/messages', [\App\Http\Controllers\Admin\DiscussionController::class, 'fetchMessages'])->name('discussions.chat.fetch');

        // Rekening bank admin
        Route::resource('admin-bank-accounts', AdminBankAccountController::class)->only(['store','update','destroy']);

        // Kupon Diskon
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->only(['store','update','destroy']);

        // Kelola User
        Route::resource('users', AdminUserController::class)->only(['index','create','store','show','edit','update','destroy']);

        // Kelola Mentor
        Route::get('mentors', [\App\Http\Controllers\Admin\MentorManageController::class, 'index'])
            ->name('mentors.index');

        // Settings
        Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    });

// Mentor routes
Route::middleware(['auth','role:mentor'])
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {
        Route::get('/', [MentorDashboardController::class, 'index'])->name('dashboard');
        
        // Course Management
        Route::resource('courses', \App\Http\Controllers\Mentor\CourseController::class);
        Route::get('courses/{mentor}/{course}', [\App\Http\Controllers\Mentor\CourseController::class, 'showBySlug'])->name('courses.show.slug');
        Route::get('courses/{mentor}/{course}/edit', [\App\Http\Controllers\Mentor\CourseController::class, 'editBySlug'])->name('courses.edit.slug');
        Route::put('courses/{mentor}/{course}', [\App\Http\Controllers\Mentor\CourseController::class, 'updateBySlug'])->name('courses.update.slug');
        Route::delete('courses/{mentor}/{course}', [\App\Http\Controllers\Mentor\CourseController::class, 'destroyBySlug'])->name('courses.destroy.slug');
        Route::get('courses/{course}/modules/{module}', [\App\Http\Controllers\Mentor\CourseController::class, 'showModule'])->name('courses.modules.show');
        Route::post('courses/{course}/modules/{module}/lessons', [\App\Http\Controllers\Mentor\CourseController::class, 'storeLesson'])->name('courses.modules.lessons.store');
        Route::get('courses/{course}/modules/{module}/lessons/{lesson}', [\App\Http\Controllers\Mentor\CourseController::class, 'showLesson'])->name('courses.modules.lessons.show');
        Route::put('courses/{course}/modules/{module}/lessons/{lesson}', [\App\Http\Controllers\Mentor\CourseController::class, 'updateLesson'])->name('courses.modules.lessons.update');
        Route::delete('courses/{course}/modules/{module}/lessons/{lesson}', [\App\Http\Controllers\Mentor\CourseController::class, 'destroyLesson'])->name('courses.modules.lessons.destroy');
        Route::post('courses/{course}/modules', [\App\Http\Controllers\Mentor\CourseController::class, 'storeModule'])->name('courses.modules.store');
        // Quiz per modul: hanya satu
        Route::post('courses/{course}/modules/{module}/quiz', [\App\Http\Controllers\Mentor\QuizController::class, 'storeForModule'])->name('courses.modules.quiz.store');
        Route::post('courses/{course}/modules/{module}/quiz/questions', [\App\Http\Controllers\Mentor\QuizQuestionController::class, 'store'])->name('courses.modules.quiz.questions.store');
        
        // Ebook Management
        Route::resource('ebooks', \App\Http\Controllers\Mentor\EbookController::class);

        // Navbar pages
        Route::view('notifications', 'pages.mentor.notifications')->name('notifications');
        Route::view('settings', 'pages.mentor.settings')->name('settings');
        Route::get('profile', \App\Http\Controllers\Mentor\ProfileController::class.'@edit')->name('profile');
        Route::post('profile', \App\Http\Controllers\Mentor\ProfileController::class.'@update')->name('profile.update');
        Route::post('profile/documents', \App\Http\Controllers\Mentor\ProfileController::class.'@storeDocument')->name('profile.documents.store');
        Route::post('profile/documents/bulk', \App\Http\Controllers\Mentor\ProfileController::class.'@storeDocumentsBulk')->name('profile.documents.bulk');

        // Discussions
        Route::get('discussions', [\App\Http\Controllers\Mentor\DiscussionController::class, 'index'])->name('discussions.index');
        Route::get('discussions/{group}/chat', [\App\Http\Controllers\Mentor\DiscussionController::class, 'chat'])->name('discussions.chat');
        Route::post('discussions/{group}/chat', [\App\Http\Controllers\Mentor\DiscussionController::class, 'postMessage'])->name('discussions.chat.post');
        Route::get('discussions/{group}/messages', [\App\Http\Controllers\Mentor\DiscussionController::class, 'fetchMessages'])->name('discussions.chat.fetch');
    });

// Admin mentor verification detail routes
Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('mentor-verifications/{user}', [\App\Http\Controllers\Admin\MentorVerificationController::class, 'show'])->name('mentor_verifications.show');
        Route::post('mentor-verifications/{user}/approve-all', [\App\Http\Controllers\Admin\MentorVerificationController::class, 'approveUser'])->name('mentor_verifications.approve_user');
        Route::post('mentor-verifications/{user}/reject-all', [\App\Http\Controllers\Admin\MentorVerificationController::class, 'rejectUser'])->name('mentor_verifications.reject_user');
    });

// Debug route to test mentor login
Route::get('/debug-login', function() {
    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user) {
        return 'No user logged in. Please login first.';
    }
    
    return [
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'mentor_dashboard_url' => route('mentor.dashboard'),
        'admin_dashboard_url' => route('admin.dashboard'),
        'home_url' => route('home'),
        'current_intended_url' => session()->get('url.intended', 'none'),
    ];
});
