<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\OtpToken;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // ── Show forgot password page ──────────────────
    public function showPage()
    {
        return view('forget-password');
    }

    // ── Handle email + captcha submit ──────────────
    public function submit(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'captcha' => 'required',
        ]);

        // ── CAPTCHA check ──────────────────────────
        if (strtoupper(trim($request->captcha)) !== session('captcha')) {
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

        // ── Generate OTP in otp_tokens table ─
        OtpToken::where('user_id', $user->id)
            ->where('flow', 'forgot')
            ->whereNull('used_at')
            ->delete();

        $otp = (string) rand(100000, 999999);

        $token = OtpToken::create([
            'user_id' => $user->id,
            'flow' => 'forgot',
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addSeconds(30),
        ]);

        session([
            'otp_user_id' => $user->id,
            'otp_token_id' => $token->id,
            'otp_flow' => 'forgot'
        ]);

        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Password Reset OTP');
        });

        return redirect('/verify-otp')->with('success', 'OTP sent to your email.');
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