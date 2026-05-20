<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Letter - {{ $patient->patient_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2E37A4;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2E37A4;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
            font-style: italic;
            margin-bottom: 10px;
        }
        .header .doctor-name {
            font-size: 16px;
            font-weight: bold;
            color: #2E37A4;
            margin-bottom: 5px;
        }
        .header .doctor-qualifications {
            font-size: 12px;
            color: #555;
            margin-bottom: 15px;
        }
        .header .contact-info {
            font-size: 11px;
            color: #666;
            line-height: 1.8;
        }
        .patient-info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .patient-info-box .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .patient-info-box .label {
            font-weight: bold;
            color: #2E37A4;
        }
        .content {
            margin-bottom: 25px;
            text-align: justify;
        }
        .content h3 {
            color: #2E37A4;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .content p {
            margin-bottom: 10px;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-section h4 {
            color: #2E37A4;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-section ul {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        .info-section li {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-left: auto;
            margin-top: 40px;
            padding-top: 5px;
        }
        .print-button {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .print-button button {
            padding: 10px 30px;
            background: #2E37A4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        .print-button button:hover {
            background: #1e2575;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button (Hidden when printing) -->
    <div class="print-button">
        <button onclick="window.print()">🖨️ Print Welcome Letter</button>
        <button onclick="window.close()">Close</button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>E-Bio-Cares</h1>
            <div class="subtitle">A Holistic Wellness Centre</div>
            <div class="doctor-name">Dr. Jaswinder Singh</div>
            <div class="doctor-qualifications">B.E.M.S, M.D (Electropathy Medicine)</div>
            <div class="contact-info">
                www.ebiocares.in<br>
                VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR<br>
                Contact: 98720-01445, 180012301445<br>
                GSTIN: 03BHTPS6858P1Z4 | PAN: BHTPS6858P
            </div>
        </div>

        <!-- Patient Info -->
        <div class="patient-info-box">
            <div class="row">
                <span class="label">Patient ID:</span>
                <span>{{ $patient->patient_id }}</span>
            </div>
            <div class="row">
                <span class="label">Date:</span>
                <span>{{ now()->format('d-m-Y') }}</span>
            </div>
            <div class="row">
                <span class="label">Patient Name:</span>
                <span>{{ strtoupper($patient->first_name . ' ' . $patient->last_name) }}</span>
            </div>
            <div class="row">
                <span class="label">Mobile:</span>
                <span>{{ $patient->phone }}</span>
            </div>
        </div>

        <!-- Welcome Content -->
        <div class="content">
            <p><strong>Dear {{ strtoupper($patient->first_name) }},</strong></p>
            
            <p>Welcome to E-Bio-Cares. We would like to take this opportunity to welcome you to our Clinic. This letter contains answers to some of the most commonly asked questions by patients entering our practice. We hope you will find this information useful.</p>
        </div>

        <!-- Office Hours -->
        <div class="info-section">
            <h4>Office Hours</h4>
            <p>Monday through Saturday from <strong>9:00 AM to 5:00 PM</strong></p>
        </div>

        <!-- Practice Philosophy -->
        <div class="info-section">
            <h4>Our Practice Philosophy</h4>
            <p>Our practice philosophy is to try for early detection, early intervention and prevention. Regularly scheduled visits allow us to better assist you in identifying and managing any chronic health problems you may have.</p>
        </div>

        <!-- Important Instructions -->
        <div class="info-section">
            <h4>Important Instructions</h4>
            <ul>
                <li>Please make a complete list of all medications that you are currently taking and bring it with you to your first visit.</li>
                <li>For all subsequent visits we will provide you with a current list of medications for your review at each follow up visit.</li>
                <li>Please arrive 10-15 minutes early for your appointments to complete any necessary paperwork.</li>
                <li>Bring all previous medical reports, test results, and imaging studies if applicable.</li>
            </ul>
        </div>

        <!-- Contact Information -->
        <div class="info-section">
            <h4>Need Assistance?</h4>
            <p>If you have any questions or need further clarification of our practice philosophy or our policies, please do not hesitate to contact our office for assistance.</p>
            <p><strong>Phone:</strong> 98720-01445, 180012301445<br>
            <strong>Email:</strong> info@ebiocares.in<br>
            <strong>Website:</strong> www.ebiocares.in</p>
        </div>

        <!-- Closing -->
        <div class="content">
            <p>We look forward to serving you and helping you achieve optimal health and wellness.</p>
            <p>Warm Regards,</p>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="signature-line">
                <strong>Dr. Jaswinder Singh</strong><br>
                B.E.M.S, M.D (Electropathy Medicine)<br>
                E-Bio-Cares
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>E-Bio-Cares - A Holistic Wellness Centre<br>
            VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR<br>
            Contact: 98720-01445, 180012301445 | www.ebiocares.in</p>
        </div>
    </div>
</body>
</html>