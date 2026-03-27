<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FormController extends Controller
{
    public function index()
    {
        return view('form'); // your form page
    }

    public function submit(Request $request)
    {

        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email',
            'phone'   => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'message' => 'required|string',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageData = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $type = $file->getMimeType();
            $imageData = 'data:' . $type . ';base64,' . base64_encode(file_get_contents($file));
        }

                User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'image'    => $imageData,
            'password' => Hash::make('123456'),   
        ]);

        
        return redirect('/form')->with('success', 'Form submitted successfully!');
    }
}