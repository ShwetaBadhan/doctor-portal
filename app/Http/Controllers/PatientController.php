<?php

namespace App\Http\Controllers;

use App\Models\Patient;
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
        $patients = Patient::latest()->get(); // Don't forget paginate!
        return view('pages.patients.patients', compact('patients')); // ✅ Fixed path
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
            $data['profile_image'] = $request->file('profile_image')->store('patients', 'public');
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
        return view('pages.patients.patient-details', compact('patient')); // ✅ Fixed path
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
            // Delete old image
            if ($patient->profile_image) {
                Storage::disk('public')->delete($patient->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('patients', 'public');
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
}