<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Diagnosis Report - {{ $patient->patient_id }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #d32f2f;
            font-size: 22px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            font-weight: 700;
        }
        .header .tagline {
            font-size: 11px;
            color: #555;
            margin-bottom: 5px;
        }
        .header .clinic-info {
            font-size: 10px;
            color: #444;
        }
        /* Title */
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-decoration: underline;
            text-underline-offset: 4px;
        }
        /* Patient Meta */
        .patient-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .patient-meta strong {
            font-weight: 600;
        }
        /* Vitals Table */
        .vitals-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .vitals-table th, .vitals-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .vitals-table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        /* Diagnosis & Remarks */
        .section-box {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 2px;
        }
        .content-text {
            text-align: justify;
            margin-bottom: 5px;
            font-size: 11px;
        }
        /* Three Column Layout: Symptoms & Medicine */
        .columns-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .symptoms-col {
            width: 35%;
        }
        .medicines-col {
            width: 65%;
        }
        .symptoms-list {
            font-size: 11px;
            margin: 0;
            padding-left: 15px;
        }
        .symptoms-list li {
            margin-bottom: 2px;
        }
        .medicine-list {
            font-size: 11px;
            margin: 0;
            padding-left: 15px;
        }
        .medicine-list li {
            margin-bottom: 4px;
        }
        .medicine-item {
            display: block;
        }
        .medicine-dosage {
            font-weight: bold;
        }
        /* Footer / Notes */
        .footer-notes {
            margin-top: 20px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
            font-weight: bold;
        }
        
        /* Print Button - Hidden in PDF */
        .no-print {
            text-align: center;
            padding: 20px;
            background: #eee;
        }
        .btn {
            padding: 10px 20px;
            background: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-print {
            background: #333;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn" onclick="window.print()">🖨️ Print / Save PDF</button>
        <a href="{{ url()->previous() }}" class="btn btn-print">← Back</a>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>E-Bio Cares</h1>
            <div class="tagline">A Holistic Wellness Centre</div>
            <div class="clinic-info">
                VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR<br>
                Contact: 98720-01445, 180012301445 | www.ebiocares.in<br>
                GSTIN: 03BHTPS6858P1Z4 | PAN: BHTPS6858P
            </div>
        </div>

        <!-- Report Title -->
        <div class="report-title">Diagnose Report</div>

        <!-- Patient Meta -->
        <div class="patient-meta">
            <div>
                <strong>Patient:</strong> {{ strtoupper($patient->first_name . ' ' . $patient->last_name) }} 
                <br>
                <strong>PID:</strong> {{ $patient->patient_id }}
                @if($patient->age) <br><strong>Age:</strong> {{ $patient->age }} Years @endif
            </div>
            <div style="text-align: right;">
                <strong>Date:</strong> {{ $reportDate ? date('d-m-Y', strtotime($reportDate)) : now()->format('d-m-Y') }}
            </div>
        </div>

        <!-- Vitals Table -->
        <table class="vitals-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Vat</th>
                    <th>PIT</th>
                    <th>Kuff</th>
                    <th>BP</th>
                    <th>Temp</th>
                    <th>Weight</th>
                    <th>Pulse</th>
                    <th>Tongue</th>
                    <th>Nails</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $reportDate ? date('d-m-Y', strtotime($reportDate)) : now()->format('d-m-Y') }}</td>
                    <td>{{ $patient->vat ?? 0 }}</td>
                    <td>{{ $patient->pit ?? 0 }}</td>
                    <td>{{ $patient->kuff ?? 0 }}</td>
                    <td>{{ $patient->bp ?? '0/0' }}</td>
                    <td>{{ $patient->temp ?? '0' }}</td>
                    <td>{{ $patient->weight ? $patient->weight . ' KG' : '0' }}</td>
                    <td>{{ $patient->pulse ?? '0' }}</td>
                    <td>{{ $patient->tongue ?? '0' }}</td>
                    <td>{{ $patient->nails ?? '0' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Diagnosis -->
        <div class="section-box">
            <div class="section-title">Diagnose:-</div>
            <p class="content-text">
                {{ $patient->medical_notes ?? 'AUTISM, ADHD, SPEECH DISORDER' }}
            </p>
        </div>

        <!-- Remarks -->
        <div class="section-box">
            <div class="section-title">Remarks:-</div>
            <p class="content-text">VSIT</p>
        </div>

        <!-- Three Columns: Symptoms & Medicine -->
        <div class="columns-container">
            <!-- Left: Symptoms -->
            <div class="symptoms-col">
                <div class="section-title">Existing Symptoms</div>
                <ul class="symptoms-list">
                    @foreach(($existingSymptoms ?? $patient->existing_symptoms ?? []) as $sym)
                        <li>{{ $sym }}</li>
                    @endforeach
                </ul>
                <br>
                <div class="section-title">Non Existing Symptoms</div>
                <ul class="symptoms-list">
                    @foreach(($nonExistingSymptoms ?? $patient->non_existing_symptoms ?? []) as $sym)
                        <li>{{ $sym }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Right: Medicine -->
            <div class="medicines-col">
                <div class="section-title">Medicine</div>
                <ol class="medicine-list">
                    @foreach($medicines as $index => $med)
                        <li>
                            <span class="medicine-item">
                                {{ $med['name'] }} 
                                @if($med['dosage']) <span class="medicine-dosage">-----> {{ $med['dosage'] }}</span> @endif
                                @if($med['quantity']) <span class="medicine-dosage">-----> {{ $med['quantity'] }}</span> @endif
                                @if($med['instructions']) <span>({{ $med['instructions'] }})</span> @endif
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="footer-notes">
            RE (APPLY ON RIGHT SIDE ALL POINTS)<br>
            YE (APPLY ON LEFT SIDE ALL POINTS)
        </div>

        <!-- Signature -->
        <div class="signature">
            <br><br><br>
            __________________________<br>
            Dr. Jaswinder Singh
        </div>
    </div>

</body>
</html>