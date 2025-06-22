<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $otp = rand(100000, 999999);

        // Simpan ke session
        Session::put('reset_user_id', $user->id);
        Session::put('reset_otp', $otp);
        Session::put('reset_otp_expire', now()->addMinutes(5));

        // Override konfigurasi mail sementara
        config([
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.port' => 587,
            'mail.mailers.smtp.encryption' => 'tls',
            'mail.mailers.smtp.username' => 'ilhamdadunet@gmail.com',
            'mail.mailers.smtp.password' => 'jude jrzr aowg fzup',
            'mail.from.address' => 'ilhamdadunet@gmail.com',
            'mail.from.name' => 'Reset OTP - Sistem Pendukung Keputusan',
        ]);

        try {
            Mail::mailer('smtp')->raw("Kode OTP reset password Anda adalah: $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode OTP Reset Password');
            });

            return back()->with('status', 'Kode OTP telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. ' . $e->getMessage()]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (
            $request->otp == Session::get('reset_otp') &&
            now()->lessThan(Session::get('reset_otp_expire'))
        ) {
            return redirect()->route('password.reset.form');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
    }

    public function showResetForm()
    {
        if (! Session::has('reset_user_id')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-form');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);

        $user = User::find(Session::get('reset_user_id'));

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            Session::forget(['reset_user_id', 'reset_otp', 'reset_otp_expire']);

            return redirect()->route('login')->with('status', 'Password berhasil diubah.');
        }

        return redirect()->route('password.request')->withErrors(['email' => 'User tidak ditemukan.']);
    }
}
