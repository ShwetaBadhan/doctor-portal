<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\MedicineGroup;
use App\Models\PatientMedicine;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $patients = Patient::latest()->get();
    
    // Add medicine groups for modal
    $medicineGroups = \App\Models\MedicineGroup::where('is_active', true)
        ->withCount('medicines')
        ->orderBy('name')
        ->get();
    
    return view('pages.patients.patients', compact('patients', 'medicineGroups'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $symptoms = $this->getSymptomsList();
        $diagnoses = $this->getDiagnosisList();
        return view('pages.patients.create-patients', compact('symptoms', 'diagnoses'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(PatientRequest $request)
{
    $data = $request->validated();
    
    // Generate Patient ID
    $data['patient_id'] = Patient::generatePatientId();
    
    // Calculate Age
    $data['age'] = Patient::calculateAge($data['dob']);
    
    // Handle Profile Image
    if ($request->hasFile('profile_image')) {
        $data['profile_image'] = $request->file('profile_image')->store('patients/profiles', 'public');
    }
    
    // Handle Test Reports (NEW)
    if ($request->hasFile('test_reports')) {
        $reportPaths = [];
        foreach ($request->file('test_reports') as $file) {
            $path = $file->store('patients/reports', 'public');
            $reportPaths[] = $path;
        }
        $data['test_reports'] = $reportPaths;
    }
    
    Patient::create($data);
    
    return redirect()->route('patients.index')
        ->with('success', 'Patient registered successfully!');
}
    /**
     * Display the specified resource.
     */
   public function show(Patient $patient)
{
    $patient->load('patientMedicines.medicine');
    
    // Load medicine groups for the modal
    $medicineGroups = MedicineGroup::where('is_active', true)
        ->withCount('medicines')
        ->orderBy('name')
        ->get();

    return view('pages.patients.patient-details', compact('patient', 'medicineGroups'));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $symptoms = $this->getSymptomsList();
        $diagnoses = $this->getDiagnosisList();
        return view('pages.patients.edit-patient', compact('patient', 'symptoms', 'diagnoses')); // ✅ Fixed path
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(PatientRequest $request, Patient $patient)
{
    $data = $request->validated();
    
    // Recalculate Age
    $data['age'] = Patient::calculateAge($data['dob']);
    
    // Handle Profile Image
    if ($request->hasFile('profile_image')) {
        if ($patient->profile_image) {
            Storage::disk('public')->delete($patient->profile_image);
        }
        $data['profile_image'] = $request->file('profile_image')->store('patients/profiles', 'public');
    }
    
    // Handle Test Reports (NEW)
    if ($request->hasFile('test_reports')) {
        // Delete old reports if replacing all
        if ($request->has('replace_reports')) {
            if ($patient->test_reports) {
                foreach ($patient->test_reports as $oldReport) {
                    Storage::disk('public')->delete($oldReport);
                }
            }
        }
        
        $reportPaths = $patient->test_reports ?? [];
        foreach ($request->file('test_reports') as $file) {
            $path = $file->store('patients/reports', 'public');
            $reportPaths[] = $path;
        }
        $data['test_reports'] = $reportPaths;
    }
    
    $patient->update($data);
    
    return redirect()->route('patients.index')
        ->with('success', 'Patient updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        if ($patient->profile_image) {
            Storage::disk('public')->delete($patient->profile_image);
        }
        
        $patient->delete();
        
        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully!');
    }

    // Helper Methods
    private function getSymptomsList()
    {
        return [
            'existing' => [
                'Autism', 'ADHD', 'Speech Disorder', 'Eye Contact', 
                'Toe Walking', 'Stubborn', 'Repetitive Behaviour',
                'Seizers', 'Hand Flapping', 'Sleep Problem',
                'Choosy at Eat', 'Teeth Grinding', 'Sweating',
                'Stool Trained', 'Concentration', 'Super Hyper',
                'Hyperactive', 'Aggressive', 'Understanding',
                'Chewing Problem', 'Command Follow', 'Socialization',
                'Jumping', 'Sensory Nerves', 'Motor Nerves',
                'Self Talk', 'Self Bite', 'Bite Other', 'Self Hit',
                'Hit Other', 'Self Laugh', 'Self Cry'
            ],
            'non_existing' => [
                'Eye Contact', 'Repetitive Behaviour', 'Seizers',
                'Choosy at Food', 'Teeth Grinding', 'Sweating',
                'Concentration', 'Understanding', 'Command Follow',
                'Socialization', 'Jumping', 'Sensory Nerves',
                'Motor Nerves', 'Self Talk', 'Sleep Problem'
            ]
        ];
    }

    private function getDiagnosisList()
    {
        return [
            'Autism', 'ADHD', 'Speech Disorder', 'C.P',
            'Super Hyper', 'Hyperactive', 'Aggressive',
            'Movement', 'Upper Limb', 'Lower Limb'
        ];
    }
    public function uploadReport(Request $request, Patient $patient)
{
    $request->validate([
        'reports.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
    ]);

    $reports = $patient->test_reports ?? [];
    
    if ($request->hasFile('reports')) {
        foreach ($request->file('reports') as $file) {
            $path = $file->store('patients/reports', 'public');
            $reports[] = $path;
        }
    }
    
    $patient->update(['test_reports' => $reports]);
    
    return redirect()->back()->with('success', 'Report(s) uploaded successfully.');
}

// For deleting a report
public function deleteReport(Patient $patient, $index)
{
    $reports = $patient->test_reports ?? [];
    
    if (isset($reports[$index])) {
        // Delete file from storage
        Storage::disk('public')->delete($reports[$index]);
        // Remove from array
        unset($reports[$index]);
        // Re-index array
        $patient->update(['test_reports' => array_values($reports)]);
    }
    
    return redirect()->back()->with('success', 'Report deleted successfully.');
}
    public function assignMedicineGroup(Request $request, Patient $patient)
{
    $validated = $request->validate([
        'medicine_group_id' => 'required|exists:medicine_groups,id',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'notes' => 'nullable|string|max:500',
    ]);

    $group = MedicineGroup::with('medicines')->findOrFail($validated['medicine_group_id']);

    $assignedCount = 0;
    foreach ($group->medicines as $medicine) {
        // Duplicate check
        $exists = PatientMedicine::where('patient_id', $patient->id)
            ->where('medicine_id', $medicine->id)
            ->where('is_active', true)
            ->exists();

        if (!$exists) {
            PatientMedicine::create([
                'patient_id' => $patient->id,
                'medicine_group_id' => $group->id,
                'medicine_id' => $medicine->id,
                'dosage' => $medicine->dosage,
                'quantity' => $medicine->quantity,
                'instructions' => $medicine->instructions,
                'route' => $medicine->route,
                'sort_order' => $medicine->sort_order,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
            $assignedCount++;
        }
    }

    // Standard redirect (no AJAX)
    return redirect()->back()->with('success', "Assigned {$assignedCount} medicines from '{$group->name}' group.");
}
}