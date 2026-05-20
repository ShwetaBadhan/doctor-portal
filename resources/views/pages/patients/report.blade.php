<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patient Report - {{ $patient->patient_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #2E37A4; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0 0 5px 0; color: #2E37A4; }
        .header p { margin: 2px 0; color: #666; }
        .patient-info { margin-bottom: 20px; }
        .patient-info h3 { border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .info-item { margin-bottom: 5px; }
        .label { font-weight: bold; color: #555; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print {
            .no-print { display: none; }
            body { margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $company['name'] }}</h2>
        <p>{{ $company['address'] }}</p>
        <p>Contact: {{ $company['contact'] }} | GSTIN: {{ $company['gstin'] }} | PAN: {{ $company['pan'] }}</p>
    </div>

    <div class="patient-info">
        <h3>Patient Report</h3>
        <div class="info-grid">
            <div class="info-item"><span class="label">Patient ID:</span> {{ $patient->patient_id }}</div>
            <div class="info-item"><span class="label">Name:</span> {{ $patient->first_name }} {{ $patient->last_name }}</div>
            <div class="info-item"><span class="label">Phone:</span> {{ $patient->phone }}</div>
            <div class="info-item"><span class="label">Email:</span> {{ $patient->email ?? 'N/A' }}</div>
            <div class="info-item"><span class="label">DOB:</span> {{ $patient->dob?->format('d M Y') ?? 'N/A' }}</div>
            <div class="info-item"><span class="label">Age:</span> {{ $patient->age ?? 'N/A' }} Years</div>
            <div class="info-item"><span class="label">Gender:</span> {{ ucfirst($patient->gender) ?? 'N/A' }}</div>
            <div class="info-item"><span class="label">Blood Group:</span> {{ $patient->blood_group ?? 'N/A' }}</div>
            <div class="info-item"><span class="label">Address:</span> {{ $patient->address_1 }}{{ $patient->address_2 ? ', '.$patient->address_2 : '' }}, {{ $patient->city }}, {{ $patient->state }} {{ $patient->pincode }}</div>
            <div class="info-item"><span class="label">Primary Doctor:</span> {{ $patient->primary_doctor ?? 'N/A' }}</div>
            <div class="info-item"><span class="label">Status:</span> {{ ucfirst($patient->status) ?? 'N/A' }}</div>
            <div class="info-item"><span class="label">Registered:</span> {{ $patient->created_at?->format('d M Y') ?? 'N/A' }}</div>
        </div>
    </div>

    @if($patient->medical_notes)
    <div class="patient-info">
        <h3>Medical Notes</h3>
        <p>{{ nl2br(e($patient->medical_notes)) }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Report generated on {{ $generated_at->format('d M Y, h:i A') }}</p>
        <p>This is a system-generated report. For queries, contact {{ $company['contact'] }}.</p>
    </div>

    <!-- Print Button (hidden when printing) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2E37A4; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨️ Print / Save as PDF
        </button>
        <a href="{{ route('patients.index') }}" style="margin-left: 10px; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">← Back</a>
    </div>
</body>
</html>