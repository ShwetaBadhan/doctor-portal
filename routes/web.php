<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\RoleController;
use App\Http\Controllers\Management\PermissionController;
use App\Http\Controllers\Management\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineGroupController;
use App\Http\Controllers\MedicineController;

// ============ ROOT ROUTE ============
// Option A: Redirect root to login (Recommended)
Route::get('/', function () {
    return redirect()->route('login');
})->name('home'); // Give it a different name

// Option B: Show login directly at root (if you prefer)
// Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');


// ============ GUEST ROUTES ============
Route::middleware('guest')->group(function () {
    // ✅ GET /login → Show login form (named 'login' for forms)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    
    // ✅ POST /login → Process login (can be named or unnamed)
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});
// ============ AUTH ROUTES ============
Route::middleware('auth')->group(function () {
    
    // ✅ Single Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
    
    // ✅ Logout Route
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // ✅ Patient Resource Routes (ALL CRUD)
    Route::resource('patients', PatientController::class);
    
    // Optional: Limit to specific actions only
     Route::resource('patients', PatientController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show', 'destroy']);



     });
// doctors

Route::get('/doctors', function () {
    return view('pages.doctors.doctors');
})->name('doctors');

Route::get('/create', function () {
    return view('pages.doctors.create-doctor');
})->name('create-doctor');

Route::get('/edit', function () {
    return view('pages.doctors.edit-doctor');
})->name('edit-doctor');

Route::get('/details', function () {
    return view('pages.doctors.doctor-details');
})->name('doctor-details');

Route::get('/appointment-calendar', function () {
    return view('pages.doctors.appointment-calendar');
})->name('appointment-calendar');



Route::middleware('auth')->group(function () {
    Route::resource('appointments', AppointmentController::class);
});


// appointments

Route::middleware('auth')->group(function () {
    // ✅ This creates ALL CRUD routes with proper names
    Route::resource('appointments', AppointmentController::class);
});

// Route::get('/appointments', function () {
//     return view('pages.appointments.appointments');
// })->name('appointments');

// Route::get('/edit-appointment', function () {
//     return view('pages.appointments.edit-appointment');
// })->name('edit-appointment');

// Route::get('/new-appointments', function () {
//     return view('pages.appointments.new-appointments');
// })->name('new-appointments');

// Route::get('/appointment-consultations', function () {
//     return view('pages.appointments.appointment-consultations');
// })->name('appointment-consultations');



// patients

// Route::get('/patients', function () {
//     return view('pages.patients.patients');
// })->name('patients');


// Route::get('/create-patients', function () {
//     return view('pages.patients.create-patients');
// })->name('create-patients');


// Route::get('/edit-patients', function () {
//     return view('pages.patients.edit-patients');
// })->name('edit-patients');

// Route::get('/patient-details', function () {
//     return view('pages.patients.patient-details');
// })->name('patient-details');




// Roles CRUD
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');

// Roles Permission Management
Route::get('assign-permissions', [RoleController::class, 'managePermissions'])->name('roles.permissions.manage');
Route::post('assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.permissions.assign');


    // Permissions CRUD
Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // 👈 ADD THIS
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');



 // Medicine Groups (Simple resource)
   
    // Medicine Groups - Simple resource routes
    Route::resource('medicine-groups', MedicineGroupController::class);
    
    // Medicines - Simple resource routes  
    Route::resource('medicines', MedicineController::class);