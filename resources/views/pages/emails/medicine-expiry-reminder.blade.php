<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medicine Expiry Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2E37A4; color: #fff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
        .medicine-item { background: #fff; padding: 10px; margin: 10px 0; border-left: 4px solid #e74c3c; }
        .medicine-name { font-weight: bold; font-size: 15px; }
        .medicine-details { font-size: 13px; color: #666; margin-top: 5px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #999; }
        .badge { background: #e74c3c; color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ Medicine Expiry Reminder</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>,</p>
            
            <p>Your following medicines are about to expire in <span class="badge">{{ $daysLeft }} days</span>:</p>
            
            @foreach($medicines as $med)
                <div class="medicine-item">
                    <div class="medicine-name">{{ $med['name'] ?? $med->medicine?->name ?? 'Unknown' }}</div>
                    <div class="medicine-details">
                        <strong>Dosage:</strong> {{ $med['dosage'] ?? '-' }} | 
                        <strong>Quantity:</strong> {{ $med['quantity'] ?? '-' }}<br>
                        <strong>End Date:</strong> {{ \Carbon\Carbon::parse($med['end_date'] ?? $med->end_date)->format('d M Y') }}
                    </div>
                </div>
            @endforeach
            
            <p style="margin-top: 20px;">
                <strong>Please reorder these medicines soon</strong> to avoid any interruption in treatment.
            </p>
            
            <p>
                <strong>Need help?</strong><br>
                Contact us: 98720-01445, 180012301445<br>
                Website: www.ebiocares.in
            </p>
        </div>
        <div class="footer">
            <p>This is an automated reminder from E-Bio-Cares.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>