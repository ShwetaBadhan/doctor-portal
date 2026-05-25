<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Welcome Letter - {{ $patient->patient_id }}</title>
    <style>
        /* ✅ BLANK PAGE FIX: Page margin 0 set karo */
        @page {
            margin: 0;
            size: A4;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #000;
            background: #fff;
        }

        /* Container for Letterhead */
        .page-container {
            position: relative;
            width: 210mm;
            min-height: 297mm;
        }

        /* ✅ LETTERHEAD FIX: Absolute Background */
        .letterhead-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 100%;
            z-index: 0;
        }

        .letterhead-bg img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* ✅ CONTENT FIX: Letterhead ke upar content layo */
        .content {
            position: relative;
            z-index: 1;

            padding-top: 80mm;
            padding-left: 35mm;
            padding-right: 35mm;
            padding-bottom: 10mm;
        }

        /* Remove any default margins on first elements */
        .content>*:first-child {
            margin-top: 0;
        }

        /* Patient Box */
        .patient-box {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        .patient-box .row {
            margin-bottom: 5px;
        }

        .patient-box .label {
            font-weight: bold;
            color: #146542;
            display: inline-block;
            width: 100px;
        }

        /* Sections */
        .section {
            margin-bottom: 12px;
        }

        .section-title {
            font-weight: bold;
            color: #146542;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        /* Buttons Hidden for PDF */
        .no-print {
            display: none !important;
        }
    </style>
</head>

<body>

    <div class="page-container">
        <!-- LETTERHEAD BACKGROUND IMAGE -->
        @if (isset($letterheadBase64) && $letterheadBase64)
            <div class="letterhead-bg">
                <img src="data:image/jpeg;base64,{{ $letterheadBase64 }}" alt="Letterhead">
            </div>
        @endif

        <!-- CONTENT OVER LETTERHEAD -->
        <div class="content">

            <!-- PATIENT DETAILS -->
            <div class="patient-box">
                <div class="row">
                    <span class="label">Patient ID:</span>
                    <span>{{ $patient->patient_id }}</span>
                </div>
                <div class="row">
                    <span class="label">Date:</span>
                    <span>{{ now()->format('d-m-Y') }}</span>
                </div>
                <div class="row">
                    <span class="label">Name:</span>
                    <span>{{ strtoupper($patient->first_name . ' ' . $patient->last_name) }}</span>
                </div>
                <div class="row">
                    <span class="label">Mobile:</span>
                    <span>{{ $patient->phone ?? '-' }}</span>
                </div>
            </div>

            <!-- WELCOME TEXT -->
            <p><strong>Dear {{ strtoupper($patient->first_name) }},</strong></p>
            <p>Welcome to E-Bio-Cares. We are delighted to have you as our patient. This letter contains important
                information about our practice.</p>

            <!-- OFFICE HOURS -->
            <div class="section">
                <div class="section-title">Office Hours</div>
                <p>Mon-Sat: 9:00 AM to 5:00 PM</p>
            </div>

            <!-- PHILOSOPHY -->
            <div class="section">
                <div class="section-title">Our Philosophy</div>
                <p>Early detection, early intervention and prevention. Regular visits help us manage your health better.
                </p>
            </div>

            <!-- INSTRUCTIONS -->
            <div class="section">
                <div class="section-title">Important Instructions</div>
                <ul>
                    <li>Bring complete list of current medications</li>
                    <li>Arrive 10-15 minutes early for appointments</li>
                    <li>Bring previous medical reports & test results</li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="section">
                <div class="section-title">Need Help?</div>
                <p>
                    <strong>Need Help?</strong><br>
                    <strong>Phone:</strong> 98720-01445, 180012301445<br>
                    <strong>Email:</strong> info@ebiocares.in<br>
                    <strong>Website:</strong> www.ebiocares.in
                </p>
            </div>

            <!-- SIGNATURE -->
            <div style="margin-top: 30px; text-align: right; padding-right: 20mm;">
                <p>Warm Regards,</p>
                <br>
                <div style="border-top: 1px solid #000; width: 150px; margin-left: auto; padding-top: 5px;">
                    <strong>Dr. Jaswinder Singh</strong><br>
                    B.E.M.S, M.D
                </div>
            </div>

            <!-- FOOTER -->
            <div
                style="margin-top: 20px; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #eee; padding-top: 10px;">
                E-Bio-Cares | VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR
            </div>
        </div>
    </div>

</body>

</html>
