<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $patients = Patient::with('doctor')->get();

            return DataTables::of($patients)
                ->addColumn('patient', function ($patient) {
                    return [
                        'name' => $patient->name,
                        'image' => $patient->image ? asset('storage/' . $patient->image) : null
                    ];
                })
                ->addColumn('doctor', function ($patient) {
                    return $patient->doctor ? $patient->doctor->name : '—';
                })
                ->addColumn('contact', function ($patient) {
                    return [
                        'mail' => $patient->mail ?? '—',
                        'phone' => $patient->phone ?? '—'
                    ];
                })
                ->addColumn('actions', function ($patient) {
                    // Prepare patient data for JavaScript
                    $patientData = [
                        'id' => $patient->id,
                        'name' => $patient->name,
                        'mail' => $patient->mail,
                        'phone' => $patient->phone,
                        'address' => $patient->address,
                        'doctor' => $patient->doctor?->name ?? '—',
                        'doctor_id' => $patient->doctor_id,
                        'image' => $patient->image ? asset('storage/' . $patient->image) : null
                    ];

                    $jsonData = e(json_encode($patientData));

                    $actions = '<div class="action-icons">';
                    $actions .= '<button type="button" class="view-patient" data-patient=\'' . $jsonData . '\' title="View"'
                        . (auth()->user()->can('manage patient') ? '' : ' disabled') . '>
              <i class="bi bi-eye"></i>
          </button>';

                    $actions .= '<button type="button" class="edit-patient" data-patient=\'' . $jsonData . '\' title="Edit"'
                        . (auth()->user()->can('manage patient') ? '' : ' disabled') . '>
              <i class="bi bi-pencil-square"></i>
          </button>';

                    $actions .= '<form action="' . route('patients.destroy', $patient->id) . '" method="POST" style="display:inline;">
                ' . csrf_field() . '
                ' . method_field('DELETE') . '
                <button type="submit" class="delete-patient" title="Delete"'
                        . (auth()->user()->can('delete patient') ? '' : ' disabled') . '>
                    <i class="bi bi-trash"></i>
                </button>
             </form>';


                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $doctors = Doctor::all();
        return view('patients.index', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'mail' => 'nullable|email|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('patients', 'public');
        }

        Patient::create($validated);

        return response()->json(['success' => 'Patient added successfully']);
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'mail' => 'nullable|email|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {

            if ($patient->image) {
                Storage::disk('public')->delete($patient->image);
            }
            $validated['image'] = $request->file('image')->store('patients', 'public');
        }

        $patient->update($validated);

        return response()->json(['success' => 'Patient updated successfully']);
    }

    public function destroy(Patient $patient)
    {
        if ($patient->image) {
            Storage::disk('public')->delete($patient->image);
        }

        $patient->delete();

        return response()->json(['success' => 'Patient deleted successfully']);
    }



public function bulkDelete(Request $request)
{
    if (!auth()->user()->can('bulk delete patient')) {
        abort(403, 'Unauthorized');
    }

    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return response()->json(['error' => 'No patients selected'], 400);
    }

    $patients = Patient::whereIn('id', $ids)->get();

    foreach ($patients as $patient) {
        if ($patient->image) {
            Storage::disk('public')->delete($patient->image);
        }
        $patient->delete();
    }

    return response()->json(['success' => 'Selected patients deleted successfully']);
}

}


