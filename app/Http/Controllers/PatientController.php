<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicineGroup;
use App\Models\Medicine;
use App\Models\PatientMedicine;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\DiagnosisReport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
class PatientController extends Controller
{
     use AuthorizesRequests; 
    /**
     * Display a listing of the resource.
     */
    // App\Http\Controllers\PatientController.php

    public function index()
    {
        $patients = Patient::latest()->get();

        // Add medicine groups for modal
        $medicineGroups = MedicineGroup::where('is_active', true)
            ->withCount('medicines')
            ->orderBy('name')
            ->get();

        // ✅ NEW: Get all active medicines for the dropdown
        $allMedicines = Medicine::where('is_active', true)->orderBy('name')->get();

        return view('pages.patients.patients', compact('patients', 'medicineGroups', 'allMedicines'));
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

        // ✅ Get the 2-letter country code from the hidden input
        $countryIso = $request->input('phone_country_iso', 'IN');

        // ✅ Generate Patient ID with all initials + country code + serial number
        // Example: Shweta Badhan, Care of: Ramesh, Jalandhar, Punjab, India → SBRJPIN4501
        $data['patient_id'] = Patient::generatePatientId(
            $data['first_name'],
            $data['last_name'],
            $data['care_of_name'] ?? '',      // Care of name
            $data['city'] ?? '',              // City
            $data['state'] ?? '',             // State
            $countryIso                       // Country code (IN, US, AU, etc.)
        );

        // Save the ISO code to the database
        $data['phone_country_iso'] = $countryIso;

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

        // ✅ Get all appointments with reports
        $appointmentsWithReports = $patient->appointments()
            ->whereNotNull('reports')
            ->where('reports', '!=', '[]')
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Load medicine groups for the modal
        $medicineGroups = MedicineGroup::where('is_active', true)
            ->withCount('medicines')
            ->orderBy('name')
            ->get();

        return view('pages.patients.patient-details', compact(
            'patient',
            'medicineGroups',
            'latestAppointment',
            'appointmentsWithReports'  // ✅ Pass this to view
        ));
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

        // ✅ Get the 2-letter country code
        $countryIso = $request->input('phone_country_iso', $patient->phone_country_iso ?? 'IN');
        $data['phone_country_iso'] = $countryIso;

        // ✅ Get all values for Patient ID generation
        $firstName = $data['first_name'] ?? $patient->first_name;
        $lastName = $data['last_name'] ?? $patient->last_name;
        $careOfName = $data['care_of_name'] ?? $patient->care_of_name ?? '';
        $city = $data['city'] ?? $patient->city ?? '';
        $state = $data['state'] ?? $patient->state ?? '';

        // ✅ Regenerate Patient ID if any of these fields changed
        if (
            $firstName !== $patient->first_name ||
            $lastName !== $patient->last_name ||
            $careOfName !== $patient->care_of_name ||
            $city !== $patient->city ||
            $state !== $patient->state ||
            $countryIso !== $patient->phone_country_iso
        ) {

            $newPatientId = Patient::generatePatientId(
                $firstName,
                $lastName,
                $careOfName,
                $city,
                $state,
                $countryIso
            );

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
    /**
     * Delete report from appointment
     */
    public function deleteReport(Request $request, $appointmentDate, $reportIndex)
    {
        try {
            // Find appointment by date (since we don't have appointment ID in the combined list)
            $appointment = Appointment::whereDate('appointment_date', $appointmentDate)
                ->whereNotNull('reports')
                ->first();

            if (!$appointment) {
                return redirect()->back()->with('error', 'Appointment not found');
            }

            $reports = $appointment->reports ?? [];

            if (!isset($reports[$reportIndex])) {
                return redirect()->back()->with('error', 'Report not found');
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($reports[$reportIndex])) {
                Storage::disk('public')->delete($reports[$reportIndex]);
            }

            // Remove from array and re-index
            unset($reports[$reportIndex]);
            $appointment->update(['reports' => array_values($reports)]);

            return redirect()->back()->with('success', 'Report deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete report: ' . $e->getMessage());
        }
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
     * Download welcome letter as PDF with Letterhead Background
     */
    public function downloadWelcomeLetter(Patient $patient)
    {
        // Letterhead image ko base64 mein convert karo
        $letterheadPath = public_path('assets/img/letter/letter-head.jpg');
        $letterheadBase64 = '';
        $imageType = 'jpeg';

        if (file_exists($letterheadPath)) {
            $imageType = pathinfo($letterheadPath, PATHINFO_EXTENSION);
            $letterheadBase64 = base64_encode(file_get_contents($letterheadPath));
        }

        $data = [
            'patient' => $patient,
            'letterheadBase64' => $letterheadBase64,
            'imageType' => $imageType,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('pages.patients.welcome-letter', $data);
        $pdf->setPaper('A4');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->download('welcome-letter-' . $patient->patient_id . '.pdf');
    }

    /**
     * Send welcome letter via email with Letterhead Background
     */
    public function sendWelcomeEmail(Request $request, Patient $patient)
    {
        if (empty($patient->email)) {
            return redirect()->back()->with('error', 'Patient does not have an email address.');
        }

        try {
            $letterheadPath = public_path('assets/img/letter/letter-head.jpg');
            $letterheadBase64 = '';
            $imageType = 'jpeg';

            if (file_exists($letterheadPath)) {
                $imageType = pathinfo($letterheadPath, PATHINFO_EXTENSION);
                $letterheadBase64 = base64_encode(file_get_contents($letterheadPath));
            }

            $data = [
                'patient' => $patient,
                'letterheadBase64' => $letterheadBase64,
                'imageType' => $imageType,
                'generatedAt' => now(),
                'forEmail' => true,
            ];

            $pdf = Pdf::loadView('pages.patients.welcome-letter', $data);

            // ✅ CC email from .env
            $ccEmail = env('MAIL_CC_ADDRESS');

            Mail::send([], [], function ($message) use ($patient, $pdf, $ccEmail) {
                $message->to($patient->email)
                    ->subject('Welcome to E-Bio-Cares')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->html(view('pages.patients.welcome-email-body', ['patient' => $patient])->render())
                    ->attachData($pdf->output(), 'welcome-letter-' . $patient->patient_id . '.pdf');

                // ✅ Add CC if exists in .env
                if (!empty($ccEmail)) {
                    $message->cc($ccEmail);
                }
            });

            return redirect()->back()->with('success', 'Welcome letter sent to ' . $patient->email);
        } catch (\Exception $e) {
            Log::error('Welcome Email Failed', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
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
                'contact' => '98720-01445, 98885-01445',
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
                'name' => $pm->custom_name ?? ($pm->medicine ? $pm->medicine->name : 'Unknown Medicine'),
                'dosage' => $pm->dosage,
                'quantity' => $pm->quantity,
                'instructions' => $pm->instructions,
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
                'name' => $pm->custom_name ?? ($pm->medicine?->name ?? 'Unknown'),
                'dosage' => $pm->dosage,
                'quantity' => $pm->quantity,
                'instructions' => $pm->instructions,
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
    /**
     * Get medicines by group via AJAX
     */
    // App\Http\Controllers\PatientController.php - getMedicinesByGroup() method

    public function getMedicinesByGroup(MedicineGroup $group, Request $request)
    {
        $patientId = $request->query('patient_id');

        $medicines = $group->medicines()
            ->select('medicines.*')
            ->with(['patientMedicines' => function ($q) use ($patientId) {
                $q->where('patient_id', $patientId)
                    ->where('is_active', true)
                    ->select('id', 'patient_id', 'medicine_id', 'custom_name', 'dosage', 'quantity', 'instructions');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($medicine) use ($patientId) {
                $existing = $medicine->patientMedicines->first();
                return [
                    'id' => $medicine->id,
                    'name' => $medicine->name,
                    'code' => $medicine->code,
                    'dosage' => $existing ? $existing->dosage : $medicine->dosage,
                    'quantity' => $existing ? $existing->quantity : $medicine->quantity,
                    'instructions' => $existing ? $existing->instructions : $medicine->instructions,
                    'already_assigned' => (bool) $existing,
                    'patient_medicine_id' => $existing?->id,
                    'custom_name' => $existing?->custom_name,  // ✅ Add this
                ];
            });

        return response()->json([
            'group' => $group->name,
            'medicines' => $medicines
        ]);
    }

    /**
     * Assign medicines with individual customization
     */
    // In PatientController.php

    // App\Http\Controllers\PatientController.php

    public function assignMedicinesCustom(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'medicine_group_id' => 'required|exists:medicine_groups,id',
            'medicines' => 'nullable|array',
            'medicines.*.assign' => 'nullable|in:1',
            'medicines.*.medicine_id' => 'nullable|exists:medicines,id',
            'medicines.*.custom_name' => 'nullable|string|max:255',
            'medicines.*.dosage' => 'nullable|string|max:50',
            'medicines.*.quantity' => 'nullable|string|max:50',
            'medicines.*.patient_medicine_id' => 'nullable|exists:patient_medicines,id',
            'extra_medicines' => 'nullable|array',
            'extra_medicines.*.medicine_id' => 'nullable|exists:medicines,id',
            'extra_medicines.*.custom_name' => 'nullable|string|max:255',
            'extra_medicines.*.dosage' => 'nullable|string|max:50',
            'extra_medicines.*.quantity' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $assignedCount = 0;
        $updatedCount = 0;

        // ===== GROUP MEDICINES =====
        $group = MedicineGroup::with('medicines')->findOrFail($validated['medicine_group_id']);

        if (!empty($validated['medicines'])) {
            foreach ($validated['medicines'] as $index => $item) {
                if (empty($item['assign'])) continue;

                $medicineId = $item['medicine_id'] ?? null;
                $customName = $item['custom_name'] ?? null;
                $medicine = $medicineId ? Medicine::find($medicineId) : null;

                $data = [
                    'patient_id' => $patient->id,
                    'medicine_group_id' => $group->id,
                    'medicine_id' => $medicineId,
                    'custom_name' => $customName,
                    'dosage' => $item['dosage'] ?? ($medicine ? $medicine->dosage : null),
                    'quantity' => $item['quantity'] ?? ($medicine ? $medicine->quantity : null),
                    'route' => $medicine ? $medicine->route : null,
                    'instructions' => $medicine ? $medicine->instructions : null,
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    // ✅ sort_order HATA DIYA - Update mein change nahi hoga
                    'is_active' => true,
                ];

                if (!empty($item['patient_medicine_id'])) {
                    // ✅ UPDATING EXISTING - sort_order touch nahi karenge
                    PatientMedicine::where('id', $item['patient_medicine_id'])
                        ->where('patient_id', $patient->id)
                        ->update($data);
                    $updatedCount++;
                } else {
                    // ✅ NEW MEDICINE - sort_order set karo
                    $data['sort_order'] = $index + 1;

                    $exists = PatientMedicine::where('patient_id', $patient->id)
                        ->where(function ($q) use ($medicineId, $customName) {
                            if ($medicineId) {
                                $q->where('medicine_id', $medicineId);
                            } elseif ($customName) {
                                $q->where('custom_name', $customName);
                            }
                        })
                        ->where('is_active', true)
                        ->exists();

                    if (!$exists) {
                        PatientMedicine::create($data);
                        $assignedCount++;
                    }
                }
            }
        }

        // ===== EXTRA MEDICINES =====
        if (!empty($validated['extra_medicines'])) {
            foreach ($validated['extra_medicines'] as $index => $extraMed) {
                $medicineId = $extraMed['medicine_id'] ?? null;
                $customName = $extraMed['custom_name'] ?? null;

                if (empty($medicineId) && empty($customName)) continue;

                $medicine = $medicineId ? Medicine::find($medicineId) : null;

                $data = [
                    'patient_id' => $patient->id,
                    'medicine_group_id' => null,
                    'medicine_id' => $medicineId,
                    'custom_name' => $customName,
                    'dosage' => $extraMed['dosage'] ?? ($medicine ? $medicine->dosage : null),
                    'quantity' => $extraMed['quantity'] ?? ($medicine ? $medicine->quantity : null),
                    'route' => $medicine ? $medicine->route : null,
                    'instructions' => $medicine ? $medicine->instructions : null,
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'is_active' => true,
                    // ✅ sort_order sirf new medicines ke liye
                ];

                $exists = PatientMedicine::where('patient_id', $patient->id)
                    ->where(function ($q) use ($medicineId, $customName) {
                        if ($medicineId) {
                            $q->where('medicine_id', $medicineId);
                        } elseif ($customName) {
                            $q->where('custom_name', $customName);
                        }
                    })
                    ->where('is_active', true)
                    ->first();

                if ($exists) {
                    // ✅ UPDATING - sort_order change nahi karenge
                    $exists->update($data);
                    $updatedCount++;
                } else {
                    // ✅ NEW - sort_order set karo
                    $data['sort_order'] = PatientMedicine::where('patient_id', $patient->id)
                        ->max('sort_order') + 1;

                    PatientMedicine::create($data);
                    $assignedCount++;
                }
            }
        }

        $message = [];
        if ($assignedCount > 0) $message[] = "Assigned {$assignedCount} medicine(s)";
        if ($updatedCount > 0) $message[] = "Updated {$updatedCount} existing medicine(s)";

        return redirect()->back()->with('success', implode(' & ', $message) ?: 'No changes made.');
    }
    /**
     * Update single patient medicine (for inline edits)
     */
    public function updatePatientMedicine(Request $request, PatientMedicine $patientMedicine)
    {
        

        $validated = $request->validate([
            'dosage' => 'nullable|string|max:50',
            'quantity' => 'nullable|string|max:50',
            'instructions' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $patientMedicine->update($validated);

        return redirect()->back()->with('success', 'Medicine updated successfully.');
    }
    /**
     * Remove a single medicine from patient (AJAX)
     */
    public function removePatientMedicine(Request $request, PatientMedicine $patientMedicine)
    {
        // Optional: Authorization check
        // $this->authorize('delete', $patientMedicine);

        // Soft delete ya hard delete
        if ($request->boolean('force')) {
            $patientMedicine->delete(); // Hard delete
        } else {
            $patientMedicine->update(['is_active' => false]); // Soft delete
        }

        return redirect()->back()->with('success', 'Medicine removed successfully.');
    }
    /**
     * Preview Diagnosis Report (Browser mein dikhane ke liye)
     */
    public function previewDiagnosisReport(Patient $patient)
    {
        // ✅ Get medicines
        $patientMedicines = $patient->patientMedicines()
            ->with(['medicine'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $medicinesList = [];
        foreach ($patientMedicines as $pm) {
            $medicinesList[] = [
                'name' => $pm->custom_name ?? ($pm->medicine?->name ?? 'Unknown'),
                'dosage' => $pm->dosage,
                'quantity' => $pm->quantity,
                'instructions' => $pm->instructions,
            ];
        }

        // ✅ Latest appointment with vitals
        $appointment = $patient->appointments()
            ->where(function ($q) {
                $q->whereNotNull('vat')
                    ->orWhereNotNull('pit')
                    ->orWhereNotNull('kuff')
                    ->orWhereNotNull('bp');
            })
            ->latest()
            ->first();

        // ✅ Letterhead image base64
        $letterheadPath = public_path('assets/img/letter/letter-head.jpg');
        $letterheadBase64 = '';
        $imageType = 'jpeg';

        if (file_exists($letterheadPath)) {
            $imageType = pathinfo($letterheadPath, PATHINFO_EXTENSION);
            $letterheadBase64 = base64_encode(file_get_contents($letterheadPath));
        }

        // ✅ Symptoms decode
        $existingSymptoms = is_array($patient->existing_symptoms)
            ? $patient->existing_symptoms
            : json_decode($patient->existing_symptoms, true) ?? [];

        $nonExistingSymptoms = is_array($patient->non_existing_symptoms)
            ? $patient->non_existing_symptoms
            : json_decode($patient->non_existing_symptoms, true) ?? [];

        $data = [
            'patient' => $patient,
            'appointment' => $appointment,
            'medicines' => $medicinesList,
            'existingSymptoms' => $existingSymptoms,
            'nonExistingSymptoms' => $nonExistingSymptoms,
            'reportDate' => $appointment?->appointment_date ?? now(),
            'letterheadBase64' => $letterheadBase64,
            'imageType' => $imageType,
            'isPreview' => true, // Flag for preview mode
        ];

        return view('pages.patients.diagnosis-report', $data);
    }


    /**
     * Generate & Save Diagnosis Report
     */
    public function generateAndSaveReport(Request $request, Patient $patient)
    {
        try {
            $reportDate = $request->input('date', now()->format('Y-m-d'));

            // Check if report already exists for this date
            $existingReport = DiagnosisReport::where('patient_id', $patient->id)
                ->where('report_date', $reportDate)
                ->first();

            if ($existingReport) {
                return response()->json([
                    'success' => true,
                    'message' => 'Report already exists for this date',
                    'report_id' => $existingReport->id,
                ]);
            }

            // Get medicines
            $patientMedicines = $patient->patientMedicines()
                ->with(['medicine'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $medicinesList = [];
            foreach ($patientMedicines as $pm) {
                $medicinesList[] = [
                    'name' => $pm->custom_name ?? ($pm->medicine?->name ?? 'Unknown'),
                    'custom_name' => $pm->custom_name,
                    'dosage' => $pm->dosage,
                    'quantity' => $pm->quantity,
                    'instructions' => $pm->instructions,
                ];
            }

            // Get appointment for this date
            $appointment = $patient->appointments()
                ->whereDate('appointment_date', $reportDate)
                ->first();

            // Letterhead
            $letterheadPath = public_path('assets/img/letter/letter-head.jpg');
            $letterheadBase64 = '';
            $imageType = 'jpeg';

            if (file_exists($letterheadPath)) {
                $imageType = pathinfo($letterheadPath, PATHINFO_EXTENSION);
                $letterheadBase64 = base64_encode(file_get_contents($letterheadPath));
            }

            // Symptoms
            $existingSymptoms = is_array($patient->existing_symptoms)
                ? $patient->existing_symptoms
                : json_decode($patient->existing_symptoms, true) ?? [];

            $nonExistingSymptoms = is_array($patient->non_existing_symptoms)
                ? $patient->non_existing_symptoms
                : json_decode($patient->non_existing_symptoms, true) ?? [];

            $data = [
                'patient' => $patient,
                'appointment' => $appointment,
                'medicines' => $medicinesList,
                'existingSymptoms' => $existingSymptoms,
                'nonExistingSymptoms' => $nonExistingSymptoms,
                'reportDate' => $reportDate,
                'letterheadBase64' => $letterheadBase64,
                'imageType' => $imageType,
            ];

            // Generate PDF
            $pdf = Pdf::loadView('pages.patients.diagnosis-report', $data);
            $pdf->setPaper('A4');
            $pdf->setOption('isRemoteEnabled', true);

            // Save PDF
            $dateStr = \Carbon\Carbon::parse($reportDate)->format('d-m-Y');
            $filename = "Diagnosis_{$patient->patient_id}_{$dateStr}.pdf";
            $pdfPath = "diagnosis_reports/{$filename}";

            Storage::disk('public')->put($pdfPath, $pdf->output());

            // Save to database
            $report = DiagnosisReport::create([
                'patient_id' => $patient->id,
                'report_date' => $reportDate,
                'pdf_path' => $pdfPath,
                'report_data' => [
                    'medicines' => $medicinesList,
                    'existingSymptoms' => $existingSymptoms,
                    'nonExistingSymptoms' => $nonExistingSymptoms,
                    'appointment_id' => $appointment?->id,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report generated and saved successfully',
                'report_id' => $report->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Generate Report Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Saved Reports History
     */
    public function getReportHistory($patientId)  // ✅ Change parameter
    {
        try {
            Log::info('Getting report history for patient ID: ' . $patientId);

            // ✅ Manually fetch patient
            $patient = Patient::findOrFail($patientId);

            $reports = DiagnosisReport::where('patient_id', $patient->id)
                ->orderBy('report_date', 'desc')
                ->get();

            Log::info('Found ' . $reports->count() . ' reports for patient ' . $patient->id);

            return response()->json([
                'reports' => $reports->map(function ($report) use ($patient) {
                    return [
                        'id' => $report->id,
                        'date' => $report->report_date->format('d M Y'),
                        'download_url' => Storage::url($report->pdf_path),
                        'preview_url' => route('diagnosis-report.preview', $patient->id) . '?date=' . $report->report_date->format('Y-m-d'),
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Get Report History Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'reports' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
