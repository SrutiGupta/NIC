<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function signupPage()
    {
        return view('signup');
    }

    public function signupSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email'=>'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone'    => 'required|string|max:15',
        ]);
        
        User::create([
            'name' => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // auto hashed by model
            'phone'    => $request->phone,

        ]);
        return redirect('/signin')->with('success', 'Account created! Please sign in.');
    }
  public function signinPage()
    {
        return view('signin');
    }

    public function signinSubmit(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
        'captcha'  => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid email or password!');
    }

// ✅ Generate OTP
    $otp = rand(100000, 999999);

    // ✅ Store in session
    session([
        'otp' => $otp,
        'otp_user' => $user,
        'otp_expire' => now()->addSeconds(30)
    ]);

    // ✅ Send OTP to log (fake email)
    Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('OTP Verification');
    });

    return redirect('/verify-otp')->with('success', 'OTP sent!');
}
 public function generateCaptcha()
    {
        $captcha = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
        session(['captcha' => $captcha]);

        return response()->json(['captcha' => $captcha]);
    }

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required'
    ]);

    // ❌ Check expiry
    if (now()->gt(session('otp_expire'))) {
        return redirect('/signin')->with('error', 'OTP expired!');
    }

    // ❌ Check wrong OTP
    if ($request->otp != session('otp')) {
        return back()->with('error', 'Invalid OTP!');
    }


    // ✅ Login success
    session(['user' => session('otp_user')]);

    // clear session
    session()->forget(['otp', 'otp_user', 'otp_expire']);

    return redirect('/form')->with('success', 'Login successful!');
}
public function resendOtp()
{
    $user = session('otp_user');

    if (!$user) {
        return redirect('/signin')->with('error', 'Session expired!');
    }

    // ✅ New OTP
    $otp = rand(100000, 999999);

    session([
        'otp' => $otp,
        'otp_expire' => now()->addSeconds(30)
    ]);

    // ✅ Send again (log mail)
    \Mail::raw("Your new OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Resend OTP');
    });

    return redirect('/verify-otp')->with('success', 'OTP resent!');
}

    public function logout()
    {
        session()->forget('user');
        return redirect('/signin')->with('success', 'Logged out!');
    }
}

