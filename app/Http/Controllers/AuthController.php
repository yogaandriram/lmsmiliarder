<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\OtpVerification;
use App\Services\MailketingService;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $fallback = ($user && ($user->role === 'admin'))
                ? route('admin.dashboard')
                : route('home');
            return redirect()->intended($fallback);
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak valid.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request, MailketingService $mailer)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','min:6','confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'member',
        ]);

        // Generate OTP berlaku 1 menit dan kirim via Mailketing
        $code = (string) random_int(100000, 999999);
        $otp = OtpVerification::create([
            'user_id' => $user->id,
            'otp_code' => $code,
            'expires_at' => Carbon::now()->addMinute(),
            'created_at' => Carbon::now(),
        ]);

        $subject = 'Kode Verifikasi OTP EduLux';
        $content = view('emails.otp', [
            'name' => $user->name,
            'code' => $code,
            'expiresAt' => $otp->expires_at,
        ])->render();

        $mailer->send($user->email, $subject, $content);

        // Arahkan ke halaman verifikasi OTP
        return redirect()->route('otp.show', ['email' => $user->email])
            ->with('status', 'Akun dibuat. Kode OTP telah dikirim ke email Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}