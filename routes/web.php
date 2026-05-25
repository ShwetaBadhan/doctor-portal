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
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DashboardController;

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
    
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth']);
    
    // ✅ Logout Route
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

  // ✅ Patient Resource Routes (ALL CRUD)
    Route::resource('patients', PatientController::class);
    
    // Patient Medicine Assignment Routes (No admin prefix)
Route::get('patients/{patient}/medicines', [PatientController::class, 'medicinesTab'])
    ->name('patients.medicines');

Route::post('patients/{patient}/medicines/assign-group', [PatientController::class, 'assignMedicineGroup'])
    ->name('patients.medicines.assign-group');

Route::post('patients/{patient}/medicines/assign-individual', [PatientController::class, 'assignIndividualMedicine'])
    ->name('patients.medicines.assign-individual');

Route::put('patients/medicines/{patientMedicine}', [PatientController::class, 'updatePatientMedicine'])
    ->name('patients.medicines.update');

Route::delete('patients/medicines/{patientMedicine}', [PatientController::class, 'removePatientMedicine'])
    ->name('patients.medicines.destroy');
     // Report routes
    Route::post('{patient}/reports/upload', [PatientController::class, 'uploadReport'])
        ->name('reports.upload');
    Route::delete('{patient}/reports/{index}', [PatientController::class, 'deleteReport'])
        ->name('reports.delete');
        Route::get('{patient}/welcome-letter', [PatientController::class, 'showWelcomeLetter'])
            ->name('welcome-letter');
        Route::get('{patient}/welcome-letter/download', [PatientController::class, 'downloadWelcomeLetter'])
            ->name('welcome-letter.download');
        Route::post('{patient}/send-welcome-email', [PatientController::class, 'sendWelcomeEmail'])
            ->name('send-welcome-email');
            
    Route::get('{patient}/diagnosis-report', [PatientController::class, 'generateDiagnosisReport'])
        ->name('diagnosis-report');
    Route::get('{patient}/download-report', [PatientController::class, 'downloadReport'])
        ->name('download-report');
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
Route::get('appointments/create', [AppointmentController::class, 'create'])
    ->name('appointments.create');
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



Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

// ✅ Permissions Management Routes
Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissionsModal'])->name('roles.permissions.modal');
Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');

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


Route::post('/medicines/bulk-store', [MedicineController::class, 'bulkStore'])
    ->name('medicines.bulk-store');

     // Invoice CRUD
    Route::resource('invoices', InvoiceController::class);
    
    // Quick actions
    Route::get('invoices/print/{invoice}', [InvoiceController::class, 'print'])
        ->name('invoices.print');

         // Shipment CRUD
    Route::resource('shipments', ShipmentController::class);
    
    // Quick status update
    Route::patch('shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])
        ->name('shipments.update-status');
    
    // Dashboard counters
    // Route::get('shipments/dashboard', [ShipmentController::class, 'dashboard'])
    //     ->name('shipments.dashboard');
    Route::get('/patients/{patient}/diagnosis-report/download', [PatientController::class, 'downloadDiagnosisReport'])
    ->name('diagnosis-report.download')
    ->middleware('auth');

    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])
    ->name('invoices.download')
    ->middleware('auth');