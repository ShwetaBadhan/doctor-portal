<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Diagnosis Report - {{ $patient->patient_id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
            overflow: hidden;
            background: transparent;
        }

        body {
            position: relative;
        }

        /* =========================
           LETTERHEAD BACKGROUND
        ========================== */

        .letterhead-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: -1;
        }

        .letterhead-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* =========================
           CONTENT
        ========================== */

        .content {
            position: relative;
            z-index: 2;
            padding: 68mm 20mm 20mm 25mm;
        }

        /* =========================
           TABLES
        ========================== */

        .patient-info-table,
        .vitals-table,
        .symptoms-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.96);
        }

        .patient-info-table td,
        .vitals-table td,
        .vitals-table th,
        .symptoms-grid td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 9px;
        }

        .patient-info-table .label {
            width: 18%;
            font-weight: bold;
            background: #f0f0f0;
        }

        .vitals-table th {
            text-align: center;
            background: #f0f0f0;
            font-weight: bold;
        }

        .vitals-table td {
            text-align: center;
        }

        /* =========================
           SECTION
        ========================== */

        .section {
            background: rgba(255, 255, 255, 0.96);
            padding: 10px;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            padding: 5px 8px;
            background: #f0f0f0;
            border-left: 4px solid #000;
        }

        /* =========================
           SYMPTOMS
        ========================== */

        .symptoms-grid td {
            width: 33.33%;
            vertical-align: top;
        }

        .symptoms-grid ul {
            padding-left: 18px;
        }

        .symptoms-grid li {
            margin-bottom: 4px;
            font-size: 9px;
        }

        /* =========================
           MEDICINES
        ========================== */

        .medicine-list {
            padding-left: 18px;
            font-size: 9px;
            line-height: 1.5;
        }

        .medicine-list li {
            margin-bottom: 4px;
        }

        .two-column-medicines {
            width: 100%;
        }

        .two-column-medicines table {
            width: 100%;
        }

        .two-column-medicines td {
            width: 50%;
            vertical-align: top;
        }

        /* =========================
           SIGNATURE
        ========================== */

        .signature {
            margin-top: 35px;
            text-align: right;
            padding-right: 20px;
            font-size: 10px;
            font-weight: bold;
        }

        /* =========================
           PREVIEW TOOLBAR
        ========================== */

        .preview-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #2c3e50;
            padding: 12px 20px;
            z-index: 9999;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-toolbar .title {
            color: #fff;
            font-size: 14px;
            font-weight: bold;
        }

        .preview-toolbar .buttons {
            display: flex;
            gap: 10px;
        }

        .preview-toolbar button,
        .preview-toolbar a {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .back {
            background: #7f8c8d;
        }

        .print {
            background: #3498db;
        }

        .download {
            background: #27ae60;
        }

        /* =========================
           PRINT
        ========================== */

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            .preview-toolbar {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    {{-- PREVIEW TOOLBAR --}}
    @if(isset($isPreview) && $isPreview)
    <div class="preview-toolbar">

        <div class="title">
            Diagnosis Report Preview - {{ $patient->patient_id }}
        </div>

        <div class="buttons">

            <button onclick="window.history.back()" class="back">
                Back
            </button>

           

            <a href="{{ route('diagnosis-report.download', $patient->id) }}"
               class="download">
                Download PDF
            </a>

        </div>

    </div>
    @endif

    {{-- LETTERHEAD --}}
    @if($letterheadBase64)
    <div class="letterhead-bg">
        <img src="data:image/jpeg;base64,{{ $letterheadBase64 }}"
             alt="Letterhead">
    </div>
    @endif

    {{-- CONTENT --}}
    <div class="content">

        {{-- PATIENT INFO --}}
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

        {{-- VITALS --}}
        <table class="vitals-table">

            <thead>
                <tr>
                    <th>Delusion</th>
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
                    <td>{{ $appointment->vat ?? ($patient->delusion ?? '-') }}</td>
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

        {{-- DIAGNOSIS --}}
        <div class="section">

            <div class="section-title">
                Diagnosis
            </div>

            <div>
                {{ $patient->medical_notes ?? 'AUTISM, ADHD, SPEECH DISORDER' }}
            </div>

        </div>

        {{-- SYMPTOMS --}}
        <div class="section">

            <div class="section-title">
                Symptoms Assessment
            </div>

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
                            <strong>C.P:</strong>
                            {{ strtoupper($patient->cp ?? 'NO') }}
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

        {{-- MEDICINES --}}
        <div class="section">

            <div class="section-title">
                Medicine Prescription
            </div>

            @if(count($medicines) > 0)

            @php
                $useTwoColumns = count($medicines) > 14;

                $totalMeds = count($medicines);

                $midPoint = ceil($totalMeds / 2);

                $leftMeds = array_slice($medicines, 0, $midPoint);

                $rightMeds = array_slice($medicines, $midPoint);

                $startNumber = count($leftMeds) + 1;
            @endphp

            @if($useTwoColumns)

            <div class="two-column-medicines">

                <table>
                    <tr>

                        <td>

                            <ol class="medicine-list">

                                @foreach($leftMeds as $med)

                                <li>
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

                        </td>

                        <td>

                            <ol class="medicine-list"
                                start="{{ $startNumber }}">

                                @foreach($rightMeds as $med)

                                <li>
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

                        </td>

                    </tr>
                </table>

            </div>

            @else

            <ol class="medicine-list">

                @foreach($medicines as $med)

                <li>
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

            @endif

            @else

            <p style="text-align:center;color:#999;">
                No medicines prescribed
            </p>

            @endif

        </div>

        {{-- SIGNATURE --}}
        <div class="signature">
            ________________________<br>
            Dr. Jaswinder Singh
        </div>

    </div>

</body>

</html>