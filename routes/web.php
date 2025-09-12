<?php

use Spatie\Permission\Models\Role;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PhoneVerificationController;
use Illuminate\Support\Facades\Route;
use App\Models\Doctor;
use App\Models\Patient;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $doctorCount = Doctor::count();
    $patientCount = Patient::count();

    return view('dashboard', [
        'doctorCount' => $doctorCount,
        'patientCount' => $patientCount,
        'status' => "You're logged in!"
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'roleindex'])->name('roleindex');
        Route::post('/', [RoleController::class, 'storerole'])->name('storerole');
        Route::put('/{role}', [RoleController::class, 'updaterole'])->name('updaterole');
        Route::delete('/{role}', [RoleController::class, 'destroyrole'])->name('destroyrole');
    });

    // Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
    //     Route::get('/', [UserController::class, 'index'])->name('index');
    //     Route::post('/', [UserController::class, 'store'])->name('store');
    //     Route::get('/create', [UserController::class, 'create'])->name('create');
    //     Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    //     Route::put('/{user}', [UserController::class, 'update'])->name('update');
    //     Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    //     Route::post('/{user}/assign-role', [UserController::class, 'assignRole'])->name('assignRole');
    // });

    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('/create', [DoctorController::class, 'create'])->name('create');
        Route::post('/', [DoctorController::class, 'store'])->name('store');
        Route::get('/{doctor}', [DoctorController::class, 'show'])->name('show');
        Route::get('/{doctor}/edit', [DoctorController::class, 'edit'])->name('edit');
        Route::put('/{doctor}', [DoctorController::class, 'update'])->name('update');
        Route::delete('/{doctor}', [DoctorController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('destroy');
    });
});


Route::post('/verify-phone', [PhoneVerificationController::class, 'verify'])->name('verify.phone.verify');
Route::get('/resend-otp', [PhoneVerificationController::class, 'resend'])->name('resend.otp');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); 

require __DIR__.'/auth.php';
