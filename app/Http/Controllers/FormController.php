<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormSubmission;

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
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'message' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageData = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $type = $file->getMimeType();
            $imageData = 'data:' . $type . ';base64,' . base64_encode(file_get_contents($file));
        }

        FormSubmission::create([
            'name'     => $request->name,
            'address'  => $request->address,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'message'  => $request->message,
            'image'    => $imageData,
        ]);

        
        return redirect('/form')->with('success', 'Form submitted successfully!');
    }
}