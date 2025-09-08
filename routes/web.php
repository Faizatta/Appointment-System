<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard')->with('status', "You're logged in!");
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('patients')->name('patients.')->middleware('auth')->group(function () {
    Route::get('/', [PatientController::class, 'index'])->name('index');
    Route::get('/create', [PatientController::class, 'create'])->name('create');
    Route::post('/', [PatientController::class, 'store'])->name('store');
    Route::get('/{patient}', [PatientController::class,'show'])->name('show');
    Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
    Route::put('/{patient}', [PatientController::class, 'update'])->name('update');
    Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('destroy');
});



Route::prefix('doctors')->name('doctors.')->middleware('auth')->group(function () {
    Route::get('/', [DoctorController::class, 'index'])->name('index');
    Route::post('/', [DoctorController::class, 'store'])->name('store'); // <-- keep below index
    Route::get('/create', [DoctorController::class, 'create'])->name('create');
    Route::get('/{doctor}', [DoctorController::class,'show'])->name('show');
    Route::get('/{doctor}/edit', [DoctorController::class, 'edit'])->name('edit');
    Route::put('/{doctor}', [DoctorController::class, 'update'])->name('update');
    Route::delete('/{doctor}', [DoctorController::class, 'destroy'])->name('destroy');
});

require __DIR__.'/auth.php';
