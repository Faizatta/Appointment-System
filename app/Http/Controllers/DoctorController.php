<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Doctor::with('patients')->select('doctors.*'); // eager load patients
            return DataTables::of($data)
                ->addColumn('doctor', function ($row) {
                    return $row->name;
                })
                ->addColumn('initials', function ($row) {
                    $words = explode(' ', $row->name);
                    $initials = '';
                    foreach ($words as $w) {
                        $initials .= strtoupper($w[0]);
                    }
                    return $initials;
                })
                ->addColumn('contact', function ($row) {
                    return [
                        'email' => $row->email,
                        'phone' => $row->phone
                    ];
                })
                ->addColumn('patients', function ($row) {
                    return $row->patients->pluck('name')->toArray();
                })
                ->addColumn('actions', function ($row) {
                    $viewBtn = '<a href="#" class="viewDoctor btn btn-sm btn-dark action-icon"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-email="' . $row->email . '"
                    data-phone="' . $row->phone . '"
                    data-address="' . $row->address . '"
                    data-image="' . ($row->image ?? '') . '"
                    data-patients="' . htmlspecialchars(json_encode($row->patients->pluck('name')->toArray())) . '">
                    <i class="bi bi-eye"></i>
                </a>';

                    $editBtn = '<a href="#" class="editDoctor btn btn-sm btn-dark action-icon"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-email="' . $row->email . '"
                    data-phone="' . $row->phone . '"
                    data-address="' . $row->address . '"
                    data-image="' . ($row->image ?? '') . '">
                    <i class="bi bi-pencil-square"></i>
                </a>';

                    $deleteBtn = '<form method="POST" action="' . route('doctors.destroy', $row->id) . '" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-doctor btn btn-sm btn-dark action-icon">
                        <i class="bi bi-trash"></i>
                    </button>
                  </form>';

                    return $viewBtn . $editBtn . $deleteBtn;
                })

                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('doctors.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:doctors,email',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        Doctor::create($data);
        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        $doctor->update($data);
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->json(['success' => true]);
    }
}
