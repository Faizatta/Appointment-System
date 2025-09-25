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
                ->addColumn('name', function ($row) {
                    $initials = '';
                    if ($row->name) {
                        $parts = explode(' ', trim($row->name));
                        $initials = count($parts) > 1
                            ? strtoupper($parts[0][0] . $parts[count($parts) - 1][0])
                            : strtoupper($parts[0][0]);
                    }

                    return [
                        'name' => $row->name,
                        'image' => $row->image ? Storage::url($row->image) : null,
                        'initials' => $initials
                    ];
                })
                ->addColumn('doctor', function ($row) {
                    return $row->doctor ? $row->doctor->name : '—';
                })
                ->addColumn('contact', function ($row) {
                    return [
                        'mail' => $row->mail ?? '—',
                        'phone' => $row->phone ?? '—',
                    ];
                })
                ->addColumn('address', function ($row) {
                    return $row->address ?? '—';
                })
                ->addColumn('actions', function ($row) {
                    $patientArray = [
                        'id' => $row->id,
                        'name' => $row->name,
                        'mail' => $row->mail,
                        'phone' => $row->phone,
                        'address' => $row->address,
                        'doctor' => $row->doctor ? $row->doctor->name : '—',
                        'doctor_id' => $row->doctor_id,
                        'image' => $row->image ? Storage::url($row->image) : null,
                    ];
                    $patientJson = e(json_encode($patientArray));

                    $viewBtn = '<button class="btn btn-sm btn-info view-patient" data-bs-toggle="tooltip" title="View" data-patient=\'' . $patientJson . '\' style="display:inline-flex; align-items:center; justify-content:center; margin-right:2px;"><i class="bi bi-eye"></i></button>';

                    $editBtn = '<button class="btn btn-sm btn-primary edit-patient" data-patient=\'' . $patientJson . '\' title="Edit"
            ' . (!(auth()->user()->hasRole('Admin') || auth()->user()->can('edit patient')) ? 'disabled' : '') . '
            style="display:inline-flex; align-items:center; justify-content:center; margin-right:2px;">
                <i class="bi bi-pencil-square"></i>
            </button>';

                    $deleteForm = '<form action="' . route('patients.destroy', $row->id) . '" method="POST" style="display:inline-flex; margin:0; padding:0;">
                    ' . csrf_field() . method_field("DELETE") . '
                    <button type="submit" class="btn btn-sm delete-patient" title="Delete"
                        ' . (!(auth()->user()->hasRole('admin') || auth()->user()->can('delete patient')) ? 'disabled' : '') . '
                        style="display:inline-flex; align-items:center; justify-content:center;">
                        <i class="bi bi-trash"></i>
                    </button>
               </form>';


                    return '<div class="action-icons">' . $viewBtn . $editBtn . $deleteForm . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $doctors = Doctor::all();
        return view('patients.index', compact('doctors'));
    }

    public function store(Request $request)
    {
        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Patient added successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Patient $patient)
    {
        $patient->load('doctor');
        return response()->json([
            'id' => $patient->id,
            'name' => $patient->name,
            'mail' => $patient->mail,
            'phone' => $patient->phone,
            'address' => $patient->address,
            'doctor' => $patient->doctor ? $patient->doctor->name : null,
            'image' => $patient->image ? Storage::url($patient->image) : null,
        ]);
    }

    public function edit(Patient $patient)
    {
        return response()->json([
            'id' => $patient->id,
            'name' => $patient->name,
            'mail' => $patient->mail,
            'phone' => $patient->phone,
            'address' => $patient->address,
            'doctor_id' => $patient->doctor_id,
            'image' => $patient->image ? Storage::url($patient->image) : null,
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'doctor_id' => 'nullable|exists:doctors,id',
                'address' => 'nullable|string|max:255',
                'mail' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($request->hasFile('image')) {
                if ($patient->image && Storage::disk('public')->exists($patient->image)) {
                    Storage::disk('public')->delete($patient->image);
                }
                $data['image'] = $request->file('image')->store('patients', 'public');
            }

            $patient->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Patient updated successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, Patient $patient)
    {
        try {
            if ($patient->image && Storage::disk('public')->exists($patient->image)) {
                Storage::disk('public')->delete($patient->image);
            }

            $patient->delete();

            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->ids;
            if (!$ids || count($ids) === 0) {
                return response()->json(['error' => 'No patients selected'], 400);
            }

            Patient::whereIn('id', $ids)->delete();
            return response()->json(['success' => 'Selected patients deleted successfully!']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
