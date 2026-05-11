<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')->latest()->paginate(10);
        // ✅ Your view path
        return view('pages.appointments.appointments', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'patient_id')->get();
        $nextId = 'AP' . str_pad(Appointment::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);
        // ✅ Your view path (new-appointments.blade.php)
        return view('pages.appointments.new-appointments', compact('patients', 'nextId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        Appointment::create($request->validated());
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // ✅ Your view path (appointment-consultations.blade.php)
        return view('pages.appointments.appointment-consultations', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'patient_id')->get();
        // ✅ Your view path (edit-appointment.blade.php)
        return view('pages.appointments.edit-appointments', compact('appointment', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete(); // Soft delete
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }
}