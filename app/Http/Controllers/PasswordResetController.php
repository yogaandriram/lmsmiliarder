<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MailketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Tampilkan form lupa kata sandi
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    // Kirim link reset ke email menggunakan Mailketing
    public function sendLink(Request $request, MailketingService $mailer)
    {
        $data = $request->validate([
            'email' => ['required','email'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            // Perbaiki chaining: gunakan withInput agar hanya field email yang dipopulasi kembali
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput(['email' => $data['email']]);
        }

        $token = Str::random(64);

        // Simpan token ke tabel password_reset_tokens (email sebagai primary key)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $user->email]);
        $subject = 'Link Atur Ulang Kata Sandi EduLux';
        $content = view('emails.reset-password', [
            'name' => $user->name,
            'resetUrl' => $resetUrl,
        ])->render();

        $sent = $mailer->send($user->email, $subject, $content);
        if (!$sent) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Coba lagi.']);
        }

        return back()->with('status', 'Link atur ulang dikirim ke email Anda.');
    }

    // Tampilkan form reset
    public function showReset(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        return view('auth.reset-password', compact('token','email'));
    }

    // Proses reset password
    public function reset(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'token' => ['required','string'],
            'password' => ['required','confirmed','min:6'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        $record = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        if (!$record || $record->token !== $data['token']) {
            return back()->withErrors(['email' => 'Token reset tidak valid.'])->withInput(['email' => $data['email']]);
        }

        // Validasi kedaluwarsa sesuai config auth
        $expireMinutes = config('auth.passwords.users.expire');
        $createdAt = Carbon::parse($record->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > $expireMinutes) {
            return back()->withErrors(['email' => 'Token reset telah kedaluwarsa. Minta link baru.'])->withInput(['email' => $data['email']]);
        }

        // Update kata sandi
        $user->forceFill(['password' => Hash::make($data['password'])])->save();

        // Hapus token agar satu kali pakai
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        // Opsional: langsung login
        Auth::login($user);

        return redirect()->route('home')->with('status', 'Kata sandi berhasil direset.');
    }
}