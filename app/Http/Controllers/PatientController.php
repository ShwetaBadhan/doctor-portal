<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\MedicineGroup;
use App\Models\PatientMedicine;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

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

        // ✅ Generate Patient ID
        $data['patient_id'] = Patient::generatePatientId(
            $data['first_name'],
            $data['last_name'],
            $data['city'] ?? ''
        );

        // Calculate Age
        $data['age'] = Patient::calculateAge($data['dob']);

        // ✅ Handle NEW symptom arrays (store as JSON)
        $symptomFields = [
            'existing_autism',
            'existing_adhd',
            'existing_cp',
            'non_existing_autism',
            'non_existing_adhd',
            'non_existing_cp',
            'additional_symptoms'
        ];

        foreach ($symptomFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode(array_values($data[$field]));
            } else {
                $data[$field] = json_encode([]);
            }
        }

        // Handle Profile Image
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('patients/profiles', 'public');
        }

        // Handle Test Reports
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
        // Load patient with relationships
        $patient->load(['patientMedicines.medicine', 'appointments']);

        // ✅ Get latest appointment with vitals (for display)
        $latestAppointment = $patient->appointments()
            ->where(function ($q) {
                $q->whereNotNull('bp')
                    ->orWhereNotNull('temp')
                    ->orWhereNotNull('pulse')
                    ->orWhereNotNull('weight');
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->first();

        // Load medicine groups for the modal
        $medicineGroups = MedicineGroup::where('is_active', true)
            ->withCount('medicines')
            ->orderBy('name')
            ->get();

        return view('pages.patients.patient-details', compact('patient', 'medicineGroups', 'latestAppointment'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
{
    $symptoms = $this->getSymptomsList();
    $diagnoses = $this->getDiagnosisList();
    
    // ✅ Decode existing symptoms from JSON (handle both array and JSON string)
    $existingSyms = is_array($patient->existing_symptoms) 
        ? $patient->existing_symptoms 
        : json_decode($patient->existing_symptoms, true) ?? [];
    
    $nonExistingSyms = is_array($patient->non_existing_symptoms) 
        ? $patient->non_existing_symptoms 
        : json_decode($patient->non_existing_symptoms, true) ?? [];
    
    return view('pages.patients.edit-patient', compact(
        'patient', 
        'symptoms', 
        'diagnoses', 
        'existingSyms',        // ✅ Added
        'nonExistingSyms'      // ✅ Added
    ));
}
    /**
     * Update the specified resource in storage.
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        $data = $request->validated();

        // ✅ Regenerate Patient ID only if name/city changed
        $firstName = $data['first_name'] ?? $patient->first_name;
        $lastName = $data['last_name'] ?? $patient->last_name;
        $city = $data['city'] ?? $patient->city ?? '';

        if ($firstName !== $patient->first_name || $lastName !== $patient->last_name || $city !== $patient->city) {
            $newPatientId = Patient::generatePatientId($firstName, $lastName, $city);
            if ($newPatientId !== $patient->patient_id) {
                $data['patient_id'] = $newPatientId;
            }
        }

        // Recalculate Age
        $data['age'] = Patient::calculateAge($data['dob']);

        // ✅ Handle NEW symptom arrays (store as JSON)
        $symptomFields = [
            'existing_autism',
            'existing_adhd',
            'existing_cp',
            'non_existing_autism',
            'non_existing_adhd',
            'non_existing_cp',
            'additional_symptoms'
        ];

        foreach ($symptomFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode(array_values($data[$field]));
            } elseif (!isset($data[$field])) {
                // If field not sent (unchecked all), save empty array
                $data[$field] = json_encode([]);
            }
        }

        // Handle Profile Image
        if ($request->hasFile('profile_image')) {
            if ($patient->profile_image) {
                Storage::disk('public')->delete($patient->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('patients/profiles', 'public');
        }

        // Handle Test Reports
        if ($request->hasFile('test_reports')) {
            $reportPaths = $patient->test_reports ?? [];
            foreach ($request->file('test_reports') as $file) {
                $path = $file->store('patients/reports', 'public');
                $reportPaths[] = $path;
            }
            $data['test_reports'] = $reportPaths;
        }

        // Handle removing existing reports
        if ($request->has('remove_reports') && $request->remove_reports) {
            $toRemove = json_decode($request->remove_reports, true) ?? [];
            $reports = $patient->test_reports ?? [];
            foreach ($toRemove as $index) {
                if (isset($reports[$index])) {
                    Storage::disk('public')->delete($reports[$index]);
                    unset($reports[$index]);
                }
            }
            $data['test_reports'] = array_values($reports);
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
                'Autism',
                'ADHD',
                'Speech Disorder',
                'Eye Contact',
                'Toe Walking',
                'Stubborn',
                'Repetitive Behaviour',
                'Seizers',
                'Hand Flapping',
                'Sleep Problem',
                'Choosy at Eat',
                'Teeth Grinding',
                'Sweating',
                'Stool Trained',
                'Concentration',
                'Super Hyper',
                'Hyperactive',
                'Aggressive',
                'Understanding',
                'Chewing Problem',
                'Command Follow',
                'Socialization',
                'Jumping',
                'Sensory Nerves',
                'Motor Nerves',
                'Self Talk',
                'Self Bite',
                'Bite Other',
                'Self Hit',
                'Hit Other',
                'Self Laugh',
                'Self Cry'
            ],
            'non_existing' => [
                'Eye Contact',
                'Repetitive Behaviour',
                'Seizers',
                'Choosy at Food',
                'Teeth Grinding',
                'Sweating',
                'Concentration',
                'Understanding',
                'Command Follow',
                'Socialization',
                'Jumping',
                'Sensory Nerves',
                'Motor Nerves',
                'Self Talk',
                'Sleep Problem'
            ]
        ];
    }

    private function getDiagnosisList()
    {
        return [
            'Autism',
            'ADHD',
            'Speech Disorder',
            'C.P',
            'Super Hyper',
            'Hyperactive',
            'Aggressive',
            'Movement',
            'Upper Limb',
            'Lower Limb'
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
// Send Welcome Email
    /**
     * Show welcome letter for printing
     */
    public function showWelcomeLetter(Patient $patient)
    {
        return view('pages.patients.welcome-letter', compact('patient'));
    }

    /**
     * Download welcome letter as PDF
     */
    public function downloadWelcomeLetter(Patient $patient)
    {
        $pdf = Pdf::loadView('pages.patients.welcome-letter', compact('patient'));
        return $pdf->download('welcome-letter-' . $patient->patient_id . '.pdf');
    }

    /**
     * Send welcome letter via email
     */
    public function sendWelcomeEmail(Request $request, Patient $patient)
    {
        if (empty($patient->email)) {
            return redirect()->back()->with('error', 'Patient does not have an email address.');
        }

        try {
            // Generate PDF
            $pdf = Pdf::loadView('pages.patients.welcome-letter', compact('patient'));

            // Send email with PDF attachment
            Mail::send([], [], function ($message) use ($patient, $pdf) {
                $message->to($patient->email)
                    ->subject('Welcome to E-Bio-Cares')
                    ->from('noreply@dsinnovativesolutions.com', 'E-Bio-Cares')
                    ->html(view('pages.patients.welcome-email-body', compact('patient'))->render())
                    ->attachData($pdf->output(), 'welcome-letter-' . $patient->patient_id . '.pdf');
            });

            return redirect()->back()->with('success', 'Welcome letter sent to ' . $patient->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    // Download Patient Report (PDF)
    public function downloadReport(Patient $patient)
    {
        // Generate PDF using DomPDF or Snappy
        // For now, we'll return a simple view that can be printed

        $data = [
            'patient' => $patient,
            'generated_at' => now(),
            'company' => [
                'name' => 'E-Bio-Cares',
                'address' => 'VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR',
                'contact' => '98720-01445, 180012301445',
                'gstin' => '03BHTPS6858P1Z4',
                'pan' => 'BHTPS6858P',
            ]
        ];

        // Option 1: Return view for browser print
        return view('pages.patients.report', $data);

        // Option 2: Generate actual PDF (if using DomPDF)
        /*
    $pdf = \PDF::loadView('pages.patients.report', $data);
    return $pdf->download("patient-report-{$patient->patient_id}.pdf");
    */
    }
    public function generateDiagnosisReport(Patient $patient)
    {
        // 1. Get Patient Data
        // Ensure symptoms are arrays

        // ✅ NEW (Fixed for PHP 8+)
        $existingSymptoms = is_array($patient->existing_symptoms)
            ? $patient->existing_symptoms
            : (json_decode($patient->existing_symptoms, true) ?? []);

        $nonExistingSymptoms = is_array($patient->non_existing_symptoms)
            ? $patient->non_existing_symptoms
            : (json_decode($patient->non_existing_symptoms, true) ?? []);
        // 2. Get Assigned Medicines for this patient
        // We use the relationship we created earlier
        $patientMedicines = $patient->patientMedicines()
            ->with(['medicine']) // Eager load medicine name
            ->orderBy('sort_order')
            ->get();

        // Format medicines for the view
        $medicinesList = [];
        foreach ($patientMedicines as $pm) {
            $medicinesList[] = [
                'name' => $pm->medicine ? $pm->medicine->name : ($pm->medicine_id ?? 'Unknown Medicine'),
                'dosage' => $pm->dosage,
                'quantity' => $pm->quantity,
                'instructions' => $pm->instructions, // e.g. "(DT)", "(AM ONLY)"
            ];
        }

        $data = [
            'patient' => $patient,
            'existingSymptoms' => $existingSymptoms,
            'nonExistingSymptoms' => $nonExistingSymptoms,
            'medicines' => $medicinesList,
            'reportDate' => now()->format('d-m-Y'), // Current date
        ];

        return view('pages.patients.diagnosis-report', $data);
    }


// PatientController.php
public function downloadDiagnosisReport(Patient $patient)
{
    // ✅ CORRECT: patientMedicines relation use karo with medicine
    $patientMedicines = $patient->patientMedicines()
        ->with(['medicine'])
        ->where('is_active', true) // Sirf active medicines
        ->orderBy('sort_order')
        ->get();

    // Medicines ko array format mein convert karo (view ke liye)
   $medicinesList = [];
foreach ($patientMedicines as $pm) {
    $medicinesList[] = [
        'name' => $pm->medicine?->name ?? 'Unknown',
        'dosage' => $pm->dosage,           // e.g., "3*4"
        'quantity' => $pm->quantity,       // e.g., "30ML"
        'instructions' => $pm->instructions, // e.g., "(DT)"
    ];
}

    // Latest appointment with vitals
    $appointment = $patient->appointments()
        ->where(function ($q) {
            $q->whereNotNull('vat')
              ->orWhereNotNull('pit')
              ->orWhereNotNull('kuff')
              ->orWhereNotNull('bp');
        })
        ->latest()
        ->first();
    
    // Letterhead image base64
    $letterheadPath = public_path('assets/img/letter/letter-head.jpg');
    $letterheadBase64 = '';
    $imageType = 'jpeg';
    
    if (file_exists($letterheadPath)) {
        $imageType = pathinfo($letterheadPath, PATHINFO_EXTENSION);
        $letterheadBase64 = base64_encode(file_get_contents($letterheadPath));
    }

    // ✅ Symptoms ko array mein convert karo (JSON decode)
    $existingSymptoms = is_array($patient->existing_symptoms) 
        ? $patient->existing_symptoms 
        : json_decode($patient->existing_symptoms, true) ?? [];
    
    $nonExistingSymptoms = is_array($patient->non_existing_symptoms) 
        ? $patient->non_existing_symptoms 
        : json_decode($patient->non_existing_symptoms, true) ?? [];

    $data = [
        'patient' => $patient,
        'appointment' => $appointment,
        'medicines' => $medicinesList, // ✅ Formatted medicines array
        'existingSymptoms' => $existingSymptoms,
        'nonExistingSymptoms' => $nonExistingSymptoms,
        'reportDate' => $appointment?->appointment_date ?? now(),
        'letterheadBase64' => $letterheadBase64,
        'imageType' => $imageType,
    ];

    $pdf = Pdf::loadView('pages.patients.diagnosis-report', $data);
    $pdf->setPaper('A4');
    $pdf->setOption('isRemoteEnabled', true);
    
    $filename = "Diagnosis_Report_" . Str::slug($patient->first_name . '_' . $patient->last_name) . ".pdf";
    
    return $pdf->download($filename);
}
}
