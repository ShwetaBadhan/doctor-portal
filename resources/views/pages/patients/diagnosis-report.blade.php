<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Diagnosis Report - {{ $patient->patient_id }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .page {
            position: relative;
            width: 210mm;
            min-height: 297mm;
        }

        /* LETTERHEAD BACKGROUND - Base64 Image */
        .letterhead-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
        }

        .letterhead-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* CONTENT - Letterhead ke upar */
        .content {
            position: relative;
            z-index: 1;
            padding: 60mm 15mm 20mm 25mm;
        }

        /* Tables aur baaki styles */
        .patient-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8px;
            background: rgba(255, 255, 255, 0.9);
        }

        .patient-info-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .label {
            font-weight: bold;
            background: rgba(240, 240, 240, 0.9);
            width: 18%;
        }

        .vitals-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
            background: rgba(255, 255, 255, 0.9);
        }

        .vitals-table th,
        .vitals-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .vitals-table th {
            background: rgba(240, 240, 240, 0.9);
        }

        .section {
           
            background: rgba(255, 255, 255, 0.9);
            padding: 8px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
            padding: 4px 6px;
            background: rgba(240, 240, 240, 0.9);
            border-left: 4px solid #000;
        }

        .symptoms-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.9);
        }

        .symptoms-grid td {
            width: 33%;
            vertical-align: top;
            border: 1px solid #000;
            padding: 6px;
        }

        .symptoms-grid ul {
            margin: 0;
            padding-left: 15px;
        }

        .symptoms-grid li {
            margin-bottom: 3px;
            font-size: 10px;
        }

        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            background: rgba(255, 255, 255, 0.9);
        }

        .medicine-table th,
        .medicine-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        .medicine-table th {
            background: rgba(240, 240, 240, 0.9);
            text-align: left;
        }

        .footer-note {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
        }

        .signature {
            margin-top: 35px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- LETTERHEAD BACKGROUND -->
        @if ($letterheadBase64)
            <div class="letterhead-bg">
                <img src="data:image/jpeg;base64,{{ $letterheadBase64 }}" alt="">
            </div>
        @endif

        <!-- CONTENT -->
        <div class="content">
            <!-- PATIENT DETAILS -->
            <table class="patient-info-table">
                <tr>
                    <td class="label">Patient Name</td>
                    <td>{{ strtoupper($patient->first_name . ' ' . $patient->last_name) }}</td>
                    <td class="label">Patient ID</td>
                    <td>{{ $patient->patient_id }}</td>
                </tr>
                <tr>
                    <td class="label">Age</td>
                    <td>{{ $patient->age ?? '-' }}</td>
                    <td class="label">Gender</td>
                    <td>{{ $patient->gender ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Date</td>
                    <td>{{ $reportDate ? date('d-m-Y', strtotime($reportDate)) : now()->format('d-m-Y') }}</td>
                    <td class="label">Weight</td>
                    <td>{{ $patient->weight ? $patient->weight . ' KG' : '-' }}</td>
                </tr>
            </table>

            <!-- VITALS TABLE -->
            <table class="vitals-table">
                <thead>
                    <tr>
                        <th>Vat</th>
                        <th>Pit</th>
                        <th>Kuff</th>
                        <th>BP</th>
                        <th>Temp</th>
                        <th>Pulse</th>
                        <th>Tongue</th>
                        <th>Nails</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- ✅ Appointment se vitals lo -->
                        <td>{{ $appointment->vat ?? ($patient->vat ?? '-') }}</td>
                        <td>{{ $appointment->pit ?? ($patient->pit ?? '-') }}</td>
                        <td>{{ $appointment->kuff ?? ($patient->kuff ?? '-') }}</td>
                        <td>{{ $appointment->bp ?? ($patient->bp ?? '-') }}</td>
                        <td>{{ $appointment->temp ?? ($patient->temp ?? '-') }}</td>
                        <td>{{ $appointment->pulse ?? ($patient->pulse ?? '-') }}</td>
                        <td>{{ $appointment->tongue ?? ($patient->tongue ?? '-') }}</td>
                        <td>{{ $appointment->nails ?? ($patient->nails ?? '-') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- DIAGNOSIS -->
            <div class="section">
                <div class="section-title">Diagnosis</div>
                <div>{{ $patient->medical_notes ?? 'AUTISM, ADHD, SPEECH DISORDER' }}</div>
            </div>

            <!-- SYMPTOMS -->
            <div class="section">
                <div class="section-title">Symptoms Assessment</div>
                <table class="symptoms-grid">
                    <tr>
                        <td>
                            <strong>Existing Symptoms</strong>
                            <ul>
                                @foreach ($existingSymptoms ?? ($patient->existing_symptoms ?? []) as $sym)
                                    <li>{{ $sym }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <strong>Non Existing Symptoms</strong>
                            <ul>
                                @foreach ($nonExistingSymptoms ?? ($patient->non_existing_symptoms ?? []) as $sym)
                                    <li>{{ $sym }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <strong>C.P Details</strong>
                            <p>
                                <strong>C.P:</strong> {{ strtoupper($patient->cp ?? 'NO') }}
                            </p>
                            @php
                                $cpMovements = is_array($patient->cp_movement)
                                    ? $patient->cp_movement
                                    : json_decode($patient->cp_movement, true) ?? [];
                            @endphp
                            <p>
                                <strong>Movement:</strong><br>
                                {{ implode(', ', $cpMovements) ?: 'N/A' }}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

       <!-- MEDICINES -->
<div class="section">
    <div class="section-title">Medicine Prescription</div>

    @if(count($medicines) > 0)

        <ol style="
            margin: 0;
            padding-left: 18px;
            font-size: 10px;
            line-height: 1.4;
        ">

            @foreach($medicines as $index => $med)

                <li style="margin-bottom: 4px;">

                    <strong>
                        {{ $med['name'] ?? $med->name ?? '-' }}
                    </strong>

                    —
                    {{ $med['dosage'] ?? $med->dosage ?? '-' }}

                    |
                    {{ $med['quantity'] ?? $med->quantity ?? '-' }}

                 

                </li>

            @endforeach

        </ol>

    @else

        <p style="text-align:center;color:#999;">
            No medicines prescribed
        </p>

    @endif
</div>


            <!-- SIGNATURE -->
            <div class="signature">
                ________________________<br>
                Dr. Jaswinder Singh
            </div>
        </div>
    </div>
</body>

</html>
