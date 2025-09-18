<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $patients = Patient::with('doctor')->select('patients.*');

            return DataTables::of($patients)
                ->addColumn('patient', function ($row) {
                    return [
                        'id'       => $row->id,
                        'name'     => $row->name,
                        'initials' => strtoupper(substr($row->name, 0, 2)),
                        'image'    => $row->image ? Storage::url($row->image) : asset('default-avatar.png'),
                    ];
                })
                ->addColumn('doctor', function ($row) {
                    return $row->doctor ? $row->doctor->name : '—';
                })
                ->addColumn('contact', function ($row) {
                    return [
                        'mail'  => $row->mail,
                        'phone' => $row->phone,
                    ];
                })
                ->addColumn('address', function ($row) {
                    return $row->address ?? '—';
                })
                ->addColumn('actions', function ($row) {
                    $permissions = [
                        'canView'   => auth()->user()->can('view patient') || auth()->user()->hasRole('admin'),
                        'canEdit'   => auth()->user()->can('edit patient') || auth()->user()->hasRole('admin'),
                        'canDelete' => auth()->user()->can('delete patient') || auth()->user()->hasRole('admin'),
                    ];

                    $patientArray = $row->toArray();
                    $patientArray['doctor'] = $row->doctor ? $row->doctor->name : '—';
                    $patientArray['contact'] = [
                        'mail'  => $row->mail,
                        'phone' => $row->phone,
                    ];
                    $patientJson = e(json_encode($patientArray));

                    $actions = '<div class="action-icons">';

                    if ($permissions['canView']) {
                        $actions .= '<button class="btn btn-sm btn-info view-patient" data-patient=\'' . $patientJson . '\' title="View">
                                        <i class="bi bi-eye"></i>
                                     </button>';
                    }

                    if ($permissions['canEdit']) {
                        $actions .= '<button class="btn btn-sm btn-primary edit-patient" data-patient=\'' . $patientJson . '\' title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                     </button>';
                    }

                    if ($permissions['canDelete']) {
                        $actions .= '<form action="' . route('patients.destroy', $row->id) . '" method="POST" style="display:inline;">
                                        ' . csrf_field() . method_field("DELETE") . '
                                        <button type="submit" class="btn btn-sm btn-danger delete-patient" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                     </form>';
                    }

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
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address'   => 'nullable|string|max:255',
            'mail'      => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('patients', 'public');
        }

        Patient::create($data);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Patient added successfully.'])
            : redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address'   => 'nullable|string|max:255',
            'mail'      => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($patient->image && Storage::disk('public')->exists($patient->image)) {
                Storage::disk('public')->delete($patient->image);
            }
            $data['image'] = $request->file('image')->store('patients', 'public');
        }

        $patient->update($data);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Patient updated successfully.'])
            : redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Request $request, Patient $patient)
    {
        if ($patient->image && Storage::disk('public')->exists($patient->image)) {
            Storage::disk('public')->delete($patient->image);
        }

        $patient->delete();

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Patient deleted successfully.'])
            : redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
