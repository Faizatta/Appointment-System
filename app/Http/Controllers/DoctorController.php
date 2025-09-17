<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $doctors = Doctor::with('patients')->get();

            return DataTables::of($doctors)
                ->addColumn('doctor', function ($row) {
                    return $row->name;
                })
                ->addColumn('image', function ($row) {
                    // return full URL of image or default avatar
                    return $row->image
                        ? Storage::url($row->image)
                        : asset('default-avatar.png');
                })
                ->addColumn('initials', function ($row) {
                    return strtoupper(substr($row->name, 0, 2));
                })
                ->addColumn('address', function ($row) {
                    return $row->address;
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
                    $imageUrl = $row->image
                        ? Storage::url($row->image)
                        : asset('default-avatar.png');

                    $viewBtn = '<button class="btn btn-sm btn-dark viewDoctor"
                                    data-id="' . $row->id . '"
                                    data-name="' . e($row->name) . '"
                                    data-email="' . e($row->email) . '"
                                    data-phone="' . e($row->phone) . '"
                                    data-address="' . e($row->address) . '"
                                    data-patients=\'' . e($row->patients->pluck('name')->toJson()) . '\'
                                    data-image="' . $imageUrl . '">
                                    <i class="bi bi-eye"></i>
                                </button>';

                    $editBtn = '<button class="btn btn-sm btn-dark editDoctor"
                                    data-id="' . $row->id . '"
                                    data-name="' . e($row->name) . '"
                                    data-email="' . e($row->email) . '"
                                    data-phone="' . e($row->phone) . '"
                                    data-address="' . e($row->address) . '"
                                    data-image="' . $imageUrl . '">
                                    <i class="bi bi-pencil-square"></i>
                                </button>';

                    $deleteBtn = '<form method="POST" action="' . route('doctors.destroy', $row->id) . '" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="delete-doctor btn btn-sm btn-dark">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                  </form>';

                    return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('doctors.index');
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
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'phone'  => 'required|string|max:255',
            'address'=> 'required|string|max:255',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
