<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #2E37A4; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #2E37A4; margin: 0; }
        .content { margin-bottom: 20px; }
        .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>E-Bio-Cares</h1>
            <p>A Holistic Wellness Centre</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $patient->first_name }},</p>
            
            <p>Welcome to E-Bio-Cares! We are delighted to have you as our patient.</p>
            
            <p>Please find attached your welcome letter which contains important information about our practice, office hours, and what to expect during your visit.</p>
            
            <p>If you have any questions, please don't hesitate to contact us at 98720-01445 or 180012301445.</p>
            
            <p>We look forward to serving you!</p>
            
            <p>Warm Regards,<br>
            <strong>Dr. Jaswinder Singh</strong><br>
            E-Bio-Cares</p>
        </div>
        
        <div class="footer">
            <p>E-Bio-Cares | VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR<br>
            Contact: 98720-01445, 180012301445 | www.ebiocares.in</p>
        </div>
    </div>
</body>
</html>