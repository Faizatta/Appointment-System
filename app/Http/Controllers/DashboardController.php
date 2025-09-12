<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorCount = Doctor::count();
        $patientCount = Patient::count();

        return view('dashboard', compact('doctorCount', 'patientCount'));
    }
}
