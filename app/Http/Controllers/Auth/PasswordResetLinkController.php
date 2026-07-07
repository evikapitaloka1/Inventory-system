<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Alur reset password tanpa server email (cocok untuk demo/submission):
     * 1. User memasukkan email.
     * 2. Jika email terdaftar, kita tetap membuat token reset resmi lewat
     *    Password broker bawaan Laravel (sama seperti token yang biasanya
     *    dikirim lewat email), lalu langsung mengarahkan user ke halaman
     *    reset password dengan token tersebut di URL.
     * 3. Jika suatu saat MAIL_MAILER diganti ke SMTP asli (mis. Gmail),
     *    cukup ganti method ini agar memanggil Password::sendResetLink()
     *    seperti pada versi standar Laravel.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email tersebut tidak terdaftar di sistem.',
            ])->onlyInput('email');
        }

        $token = Password::broker()->createToken($user);

        return redirect()->route('password.reset', ['token' => $token])
            ->with('email_prefill', $user->email)
            ->with('status', 'Email ditemukan. Silakan atur password baru Anda di bawah ini.');
    }
}
