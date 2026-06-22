<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{

    $appointments = Appointment::with('patient')
        ->whereHas('patient') 
        ->latest()
        ->get();
        
    return view('pages.appointments.appointments', compact('appointments'));
}

public function todayAppointments()
{
    $appointments = Appointment::with('patient')
        ->whereHas('patient')  
        ->whereDate('appointment_date', Carbon::today())
        ->orderBy('appointment_time', 'asc')
        ->get();

    return view('pages.appointments.appointments', compact('appointments'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'patient_id')->get();
        $nextId = 'AP' . str_pad(Appointment::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);

        // ✅ Get patient_id from query parameter
        $selectedPatient = $request->query('patient_id');

        return view('pages.appointments.new-appointments', compact('patients', 'nextId', 'selectedPatient'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(AppointmentRequest $request)
{
    $data = $request->validated();

    // Handle file uploads
    if ($request->hasFile('reports')) {
        $reportPaths = [];
        foreach ($request->file('reports') as $file) {
            $path = $file->store('appointments/reports', 'public');
            $reportPaths[] = $path;
        }
        $data['reports'] = $reportPaths;
    } else {
        $data['reports'] = [];
    }

    Appointment::create($data);

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
    $data = $request->validated();

    // ✅ Handle new file uploads
    if ($request->hasFile('reports')) {
        $reportPaths = $appointment->reports ?? []; // Keep existing reports
        
        foreach ($request->file('reports') as $file) {
            $path = $file->store('appointments/reports', 'public');
            $reportPaths[] = $path;
        }
        $data['reports'] = $reportPaths;
    }

    $appointment->update($data);

    return redirect()->route('appointments.index')
        ->with('success', 'Appointment updated successfully!');
}

// ✅ Add this method for deleting individual reports
public function deleteReport(Appointment $appointment, $index)
{
    $reports = $appointment->reports ?? [];

    if (isset($reports[$index])) {
        // Delete file from storage
        Storage::disk('public')->delete($reports[$index]);
        
        // Remove from array and re-index
        unset($reports[$index]);
        $appointment->update(['reports' => array_values($reports)]);
    }

    return redirect()->back()->with('success', 'Report deleted successfully.');
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
