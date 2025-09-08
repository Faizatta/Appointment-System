<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('doctor')->get();
        $doctors  = Doctor::all();
        return view('patients.index', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address' => 'nullable|string|max:255',
            'mail' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('patients', 'public');
        }

        Patient::create($data);

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address' => 'nullable|string|max:255',
            'mail' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($patient->image && Storage::disk('public')->exists($patient->image)) {
                Storage::disk('public')->delete($patient->image);
            }

            $data['image'] = $request->file('image')->store('patients', 'public');
        }

        $patient->update($data);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        // Delete image from storage
        if ($patient->image && Storage::disk('public')->exists($patient->image)) {
            Storage::disk('public')->delete($patient->image);
        }

        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
