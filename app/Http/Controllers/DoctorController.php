<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        try {
            $doctors = Doctor::with('patients')->get();

            return view('doctors.index', compact('doctors'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }




    }

    public function create()
    {
        return view('doctors.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|max:255|unique:doctors,email',
        'phone'  => 'required|string|max:255',
        'address'=> 'required|string|max:255',
        'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        $data = $request->only(['name', 'email', 'phone', 'address']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        Doctor::create($data);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor created successfully.');
    } catch (\Exception $e) {
        // Log error for debugging
        \Log::error('Doctor store error: '.$e->getMessage());

        return redirect()
            ->back()
            ->with('error', 'Failed to create doctor: '.$e->getMessage());
    }
}

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        try {
            $data = $request->only(['name', 'email', 'phone', 'address']);


            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('doctors', 'public');
            }

            $doctor->update($data);

            return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    public function show(Doctor $doctor)
    {

        $doctor->load('patients');
        return view('doctors.show', compact('doctor'));
    }

}
