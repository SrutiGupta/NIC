<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormSubmission;
use App\Models\LgdBlock;
use App\Models\LgdDistrict;
use App\Models\LgdState;
use App\Models\LgdSubdistrict;
use Illuminate\Validation\Rule;

class FormController extends Controller
{
    public function index()
    {
        $states = LgdState::query()
            ->select(['state_code', 'state_name'])
            ->orderBy('state_name')
            ->get();

        return view('form', compact('states'));
    }

    public function districtsByState(int $stateCode)
    {
        $districts = LgdDistrict::query()
            ->where('state_code', $stateCode)
            ->orderBy('district_name')
            ->get(['district_code', 'district_name']);

        return response()->json($districts);
    }

    public function subdistrictsByDistrict(int $districtCode)
    {
        $subdistricts = LgdSubdistrict::query()
            ->where('district_code', $districtCode)
            ->orderBy('subdistrict_name')
            ->get(['subdistrict_code', 'subdistrict_name']);

        return response()->json($subdistricts);
    }

    public function blocksByDistrict(int $districtCode)
    {
        $blocks = LgdBlock::query()
            ->where('district_code', $districtCode)
            ->orderBy('block_name')
            ->get(['block_code', 'block_name']);

        return response()->json($blocks);
    }

    public function submit(Request $request)
    {

        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'state_code' => ['required', 'integer', 'exists:lgd_states,state_code'],
            'district_code' => [
                'required',
                'integer',
                Rule::exists('lgd_districts', 'district_code')->where(function ($query) use ($request) {
                    $query->where('state_code', $request->integer('state_code'));
                }),
            ],
            'subdistrict_code' => [
                'required',
                'integer',
                Rule::exists('lgd_subdistricts', 'subdistrict_code')->where(function ($query) use ($request) {
                    $query->where('district_code', $request->integer('district_code'));
                }),
            ],
            'block_code' => [
                'required',
                'integer',
                Rule::exists('lgd_blocks', 'block_code')->where(function ($query) use ($request) {
                    $query->where('district_code', $request->integer('district_code'));
                }),
            ],
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
            'state_code' => $request->integer('state_code'),
            'district_code' => $request->integer('district_code'),
            'subdistrict_code' => $request->integer('subdistrict_code'),
            'block_code' => $request->integer('block_code'),
            'message'  => $request->message,
            'image'    => $imageData,
        ]);

        
        return redirect('/form')->with('success', 'Form submitted successfully!');
    }
}