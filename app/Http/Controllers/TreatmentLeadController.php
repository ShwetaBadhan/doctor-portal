<?php

namespace App\Http\Controllers;

use App\Models\Consultation;

class TreatmentLeadController extends Controller
{
    public function index()
    {
        $consultations = Consultation::orderBy('created_at', 'asc')->get();
        
        return view('pages.website.treatment-leads', compact('consultations'));
    }
}