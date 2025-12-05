<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use App\Services\MailketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    // Tampilkan halaman verifikasi OTP
    public function show(Request $request)
    {
        $email = $request->query('email');
        $return = $request->query('return');

        // Hitung sisa detik menuju kedaluwarsa OTP terakhir (untuk countdown UI)
        $remainingSeconds = null;
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $otp = OtpVerification::where('user_id', $user->id)
                    ->orderByDesc('created_at')
                    ->first();
                if ($otp) {
                    $remainingSeconds = Carbon::now()->diffInSeconds($otp->expires_at, false);
                    if ($remainingSeconds < 0) {
                        $remainingSeconds = 0;
                    }
                }
            }
        }

        return view('auth.verify-otp', compact('email', 'remainingSeconds','return'));
    }

    // Kirim / Request OTP baru (maks 3x per 1 jam)
    public function requestOtp(Request $request, MailketingService $mailer)
    {
        $data = $request->validate([
            'email' => ['required','email'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        // Cek rate limit: maksimal 3 request dalam 1 jam
        $windowStart = Carbon::now()->subHour();
        $requestsLastHour = OtpVerification::where('user_id', $user->id)
            ->where('created_at', '>=', $windowStart)
            ->count();

        if ($requestsLastHour >= 3) {
            return back()->withErrors(['email' => 'Batas permintaan OTP tercapai. Coba lagi setelah 1 jam.']);
        }

        // Generate OTP dan simpan, berlaku 1 menit
        $code = (string) random_int(100000, 999999);
        $otp = OtpVerification::create([
            'user_id' => $user->id,
            'otp_code' => $code,
            'expires_at' => Carbon::now()->addMinute(),
            'created_at' => Carbon::now(),
        ]);

        // Kirim email via Mailketing
        $subject = 'Kode Verifikasi OTP EduLux';
        $content = view('emails.otp', [
            'name' => $user->name,
            'code' => $code,
            'expiresAt' => $otp->expires_at,
        ])->render();

        $sent = $mailer->send($user->email, $subject, $content);

        if (!$sent) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Coba lagi.']);
        }

        return back()->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    // Verifikasi OTP
    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'code' => ['required','digits:6'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        // Ambil OTP terakhir untuk user
        $otp = OtpVerification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'OTP tidak ditemukan. Minta kode baru.']);
        }

        if (Carbon::now()->greaterThan($otp->expires_at)) {
            return back()->withErrors(['code' => 'Kode OTP sudah kedaluwarsa.']);
        }

        if ($otp->otp_code !== $data['code']) {
            return back()->withErrors(['code' => 'Kode OTP salah.']);
        }

        // Tandai email terverifikasi
        $user->forceFill(['email_verified_at' => Carbon::now()])->save();

        // (Opsional) Hapus OTP yang terpakai
        $otp->delete();

        // Loginkan user dan arahkan ke dashboard sesuai role
        Auth::login($user);
        $fallback = match($user->role) {
            'admin' => route('admin.dashboard'),
            'mentor' => route('mentor.dashboard'),
            'member' => route('member.dashboard'),
            default => route('home')
        };
        $ret = $request->input('return') ?: session('url.intended');
        return redirect($ret ?: $fallback)->with('status', 'Email berhasil diverifikasi.');
    }
}
