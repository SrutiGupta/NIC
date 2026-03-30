<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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

    if (strtoupper(trim($request->captcha)) !== session('captcha')) {
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid CAPTCHA! Please try again.');
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid email or password!');
    }

// Generate OTP
    $otp = rand(100000, 999999);

    $user->update([
    'otp' => Hash::make($otp),
    'otp_expires_at' => now()->addSeconds(30)
]);

    //  Store in session
    session([
        'otp' => $otp,
        'otp_user_id' => $user->id,
        'otp_expire' => now()->addSeconds(30)->toDateTimeString(),
        'otp_flow' => 'signin'
    ]);

    // Send OTP to log (fake email)
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

        $x = 18;
        $letters = '';
        for ($i = 0; $i < strlen($captcha); $i++) {
            $angle = rand(-12, 12);
            $y = rand(28, 38);
            $char = htmlspecialchars($captcha[$i], ENT_QUOTES, 'UTF-8');
            $letters .= "<text x=\"{$x}\" y=\"{$y}\" transform=\"rotate({$angle} {$x} {$y})\" font-size=\"24\" font-family=\"monospace\" fill=\"#ffffff\">{$char}</text>";
            $x += 24;
        }

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"160\" height=\"50\" viewBox=\"0 0 160 50\">"
            . "<rect width=\"160\" height=\"50\" fill=\"#1f2937\" rx=\"6\" ry=\"6\"/>"
            . "<line x1=\"8\" y1=\"10\" x2=\"152\" y2=\"42\" stroke=\"#6b7280\" stroke-width=\"1\"/>"
            . "<line x1=\"10\" y1=\"42\" x2=\"150\" y2=\"8\" stroke=\"#4b5563\" stroke-width=\"1\"/>"
            . $letters
            . "</svg>";

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required'
    ]);

    // Check expiry
    $otpExpire = session('otp_expire');
    $flow = session('otp_flow', 'signin');
    if (!$otpExpire || now()->gt(Carbon::parse($otpExpire))) {
        session()->forget(['otp', 'otp_user_id', 'otp_expire', 'otp_flow']);
        return redirect($flow === 'forgot' ? '/forget-password' : '/signin')->with('error', 'OTP expired!');
    }

    // Check wrong OTP
   $user = User::find(session('otp_user_id'));

if (!$user) {
    session()->forget(['otp', 'otp_user_id', 'otp_expire', 'otp_flow']);
    return redirect('/signin')->with('error', 'Session expired! Please sign in again.');
}

if (!Hash::check($request->otp, $user->otp)) {
    return back()->with('error', 'Invalid OTP!');
}


    if ($flow === 'forgot') {
        session(['verified_reset_email' => $user->email]);
        session()->forget(['otp', 'otp_user_id', 'otp_expire', 'otp_flow']);
        return redirect('/reset-password')->with('success', 'OTP verified. You can now reset your password.');
    }


    // Login success
    session(['user' => $user]);

    // clear session
    session()->forget(['otp', 'otp_user_id', 'otp_expire', 'otp_flow']);

    return redirect('/form')->with('success', 'Login successful!');
}
public function resendOtp()
{
    $user = User::find(session('otp_user_id'));
    $flow = session('otp_flow', 'signin');

    if (!$user) {
        return redirect($flow === 'forgot' ? '/forget-password' : '/signin')->with('error', 'Session expired!');
    }
    // New OTP
    $otp = rand(100000, 999999);

    $user->update([
        'otp' => Hash::make($otp),
        'otp_expires_at' => now()->addSeconds(30)
    ]);

    session([
        'otp' => $otp,
        'otp_expire' => now()->addSeconds(30)->toDateTimeString()
    ]);

    // Send again (log mail)
    Mail::raw("Your new OTP is: $otp", function ($message) use ($user, $flow) {
        $message->to($user->email)
                ->subject($flow === 'forgot' ? 'Password Reset OTP' : 'Resend OTP');
    });

    return redirect('/verify-otp')->with('success', 'OTP resent!');
}

    public function logout()
    {
        session()->forget('user');
        return redirect('/signin')->with('success', 'Logged out!');
    }
}

