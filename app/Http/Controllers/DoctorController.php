<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $doctors = Doctor::with('patients:id,doctor_id,name')
                ->select('doctors.*');

            return DataTables::of($doctors)
                ->addColumn('doctor', function ($row) {
                    return [
                        'id' => $row->id,
                        'name' => $row->name,
                        'initials' => strtoupper(substr($row->name, 0, 2)),
                        'image' => $row->image ? Storage::url($row->image) : asset('default-avatar.png'),
                    ];
                })
                ->addColumn('contact', function ($row) {
                    return [
                        'email' => $row->email ?? '—',
                        'phone' => $row->phone ?? '—',
                    ];
                })
                ->addColumn('address', fn($row) => $row->address ?? '—')
                ->addColumn('patients', function ($row) {
                    return $row->patients->isNotEmpty()
                        ? $row->patients->pluck('name')->implode(', ')
                        : '—';
                })
                ->addColumn('actions', function ($row) {
                    $doctorArray = [
                        'id' => $row->id,
                        'name' => $row->name,
                        'email' => $row->email,
                        'phone' => $row->phone,
                        'address' => $row->address,
                        'image' => $row->image ? Storage::url($row->image) : asset('default-avatar.png'),
                        'patients' => $row->patients->map(fn($p) => ['name' => $p->name]),
                    ];
                    $doctorJson = e(json_encode($doctorArray));


                    $canView = auth()->user()->hasRole('Admin') || auth()->user()->can('manage doctor');
                    $viewBtn = '<button class="btn btn-sm view-doctor" data-bs-toggle="tooltip"
                title="View" data-doctor=\'' . $doctorJson . '\' ' . ($canView ? '' : 'disabled') . '>
                <i class="bi bi-eye"></i>
            </button>';



                    $canEdit = auth()->user()->hasRole('Admin') || auth()->user()->can('manage doctor');
                    $editBtn = '<button class="btn btn-sm edit-doctor" data-doctor=\'' . $doctorJson . '\'
                                title="Edit" ' . ($canEdit ? '' : 'disabled') . '>
                                <i class="bi bi-pencil-square"></i>
                            </button>';


                    $canDelete = auth()->user()->hasRole('Admin') || auth()->user()->can('delete doctor');
                    $deleteForm = '<form action="' . route('doctors.destroy', $row->id) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . method_field("DELETE") . '
                                    <button type="submit" class="btn btn-sm delete-doctor"
                                        title="Delete" ' . ($canDelete ? '' : 'disabled') . '>
                                        <i class="bi bi-trash"></i>
                                    </button>
                               </form>';

                    return '<div class="action-icons">' . $viewBtn . $editBtn . $deleteForm . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('doctors.index');
    }

public function bulkDelete(Request $request)
{
    if (!auth()->user()->can('bulk delete ')) {
        abort(403, 'Unauthorized');
    }

    $ids = $request->ids;
    if (!$ids || count($ids) === 0) {
        return response()->json(['error' => 'No doctors selected'], 400);
    }

    Doctor::whereIn('id', $ids)->delete();

    return response()->json(['success' => 'Selected doctors deleted successfully!']);
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        Doctor::create($data);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Doctor added successfully.'])
            : redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($doctor->image && Storage::disk('public')->exists($doctor->image)) {
                Storage::disk('public')->delete($doctor->image);
            }
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        $doctor->update($data);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Doctor updated successfully.'])
            : redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Request $request, Doctor $doctor)
    {
        if ($doctor->image && Storage::disk('public')->exists($doctor->image)) {
            Storage::disk('public')->delete($doctor->image);
        }

        $doctor->delete();

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Doctor deleted successfully.'])
            : redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}
