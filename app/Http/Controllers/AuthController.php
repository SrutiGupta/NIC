<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    session(['user' => $user]);

    return redirect('/form')->with('success', 'Welcome ' . $user->name . '!');
}
 public function generateCaptcha()
    {
        $captcha = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
        session(['captcha' => $captcha]);

        return response()->json(['captcha' => $captcha]);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/signin')->with('success', 'Logged out!');
    }
}

