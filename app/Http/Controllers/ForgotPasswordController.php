<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OtpToken;

class ForgotPasswordController extends Controller
{
    // ── Show forgot password page ──────────────────
    public function showPage()
    {
        return view('forget-password');
        // /captcha route is called by the blade itself
        // CaptchaController sets session['captcha_answer'] automatically
    }

    // ── Handle email + captcha submit ──────────────
    public function submit(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'captcha' => 'required',
        ]);

        // ── CAPTCHA check ──────────────────────────
        if ($request->captcha !== session('captcha_answer')) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Incorrect CAPTCHA. Please try again.');
        }

        // ── Email exists check ─────────────────────
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'No account found with that email.');
        }

        // ── Generate OTP ───────────────────────────
        // Delete any old OTP for this email first
        OtpToken::where('email', $user->email)->delete();

        $otp = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpToken::create([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // ── Set both sessions ──────────────────────
        // otp_email   → OtpController uses this to query the DB
        // reset_email → flag that tells OtpController this is a reset flow
        session(['otp_email'   => $user->email]);
        session(['reset_email' => $user->email]);

        return redirect('/otp')->with('success', 'OTP generated. Check otp_tokens table.');
    }

    // ── Show reset password page ───────────────────
    public function showResetPage()
    {
        // Guard — only reachable after OTP is verified
        if (!session('verified_reset_email')) {
            return redirect('/forget-password')->with('error', 'Please verify your OTP first.');
        }

        return view('reset-password');
    }

    // ── Save new password ──────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $email = session('verified_reset_email');

        if (!$email) {
            return redirect('/signin')->with('error', 'Session expired. Please try again.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/signin')->with('error', 'User not found.');
        }

        // ── Update password ────────────────────────
        $user->password = Hash::make($request->password);
        $user->save();

        // ── Clear reset session ────────────────────
        session()->forget('verified_reset_email');

        return redirect('/signin')->with('success', 'Password reset! Please sign in with your new password.');
    }
}