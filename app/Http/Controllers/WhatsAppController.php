<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppController extends Controller
{
    /**
     * Send Welcome Letter to a specific patient
     */
public function sendWelcomeLetter($patientId)
{
    $patient = DB::table('patients')->where('id', $patientId)->first();
    
    if (!$patient) {
        return response()->json(['error' => 'Patient not found'], 404);
    }

    $baseUrl = env('MYOPERATOR_BASE_URL');
    $wabaApiKey = env('MYOPERATOR_WABA_API_KEY');

    $phone = $patient->phone;
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    $countryCode = '91';
    if (strlen($phone) > 10) {
        $countryCode = substr($phone, 0, 2);
        $phone = substr($phone, 2);
    }

    $patientName = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));

   $url = $baseUrl . '/chat/messages';

    $payload = [
        'phone_number_id' => env('MYOPERATOR_PHONE_NUMBER_ID'),
        'customer_country_code' => $countryCode,
        'customer_number' => $phone,
        'data' => [
            'type' => 'template',
            'context' => [
                'template_name' => 'welcome_msg',
                'body' => ['1' => $patientName]
            ]
        ]
    ];

    // ✅ AUTHORIZATION: BEARER USE KAREIN
$response = Http::withHeaders([
    'x-api-key' => env('MYOPERATOR_WABA_API_KEY'),
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
])->post($url, $payload);

  Log::info('API Keys Check', [
    'MYOPERATOR_API_KEY' => env('MYOPERATOR_API_KEY'),
    'MYOPERATOR_WABA_API_KEY' => env('MYOPERATOR_WABA_API_KEY'),
]);

    return response()->json([
        'status' => $response->successful() ? 'success' : 'error',
        'response' => $response->json() ?? $response->body(),
        'status_code' => $response->status()
    ]);
}
    /**
     * Automated Medicine Expiry Reminders
     */
    public function sendExpiryReminders()
    {
        $baseUrl = env('MYOPERATOR_BASE_URL');
        $wabaApiKey = env('MYOPERATOR_WABA_API_KEY');
        $phoneNumberId = env('MYOPERATOR_PHONE_NUMBER_ID');

        $remindDays = [10, 7, 5, 3, 2];
        $today = Carbon::today();

        $totalSent = 0;
        $results = [];

        foreach ($remindDays as $days) {
            $targetDate = $today->copy()->addDays($days);
            
            $medicines = DB::table('patientmedicine')
                ->join('patients', 'patientmedicine.patient_id', '=', 'patients.id')
                ->whereDate('patientmedicine.end_date', $targetDate)
                ->where('patientmedicine.is_active', 1)
                ->select(
                    'patients.id as patient_id',
                    'patients.first_name',
                    'patients.last_name',
                    'patients.phone',
                    'patientmedicine.custom_name as medicine_name',
                    'patientmedicine.end_date'
                )
                ->get();

            foreach ($medicines as $medicine) {
                $phone = $medicine->phone;
                $countryCode = '91';
                
                if (strlen($phone) > 10) {
                    $countryCode = substr($phone, 0, 2);
                    $phone = substr($phone, 2);
                }
                
                $phone = preg_replace('/[^0-9]/', '', $phone);

                $patientName = trim($medicine->first_name . ' ' . $medicine->last_name);
                $medicineName = $medicine->medicine_name ?? 'Medicine';
                
                $bodyParams = [
                    '1' => $patientName,
                    '2' => $medicineName,
                    '3' => (string)$days
                ];

                $payload = [
                    'phone_number_id' => $phoneNumberId,
                    'customer_country_code' => $countryCode,
                    'customer_number' => $phone,
                    'data' => [
                        'type' => 'template',
                        'context' => [
                            'template_name' => 'medicine_expiry', 
                            'body' => $bodyParams,
                        ],
                    ],
                ];

                $url = $baseUrl . '/send-message';

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'x-api-key' => $wabaApiKey, // ✅ Correct header
                    ])
                    ->post($url, $payload);
                
                Log::info("Medicine reminder sent", [
                    'patient' => $patientName,
                    'phone' => $medicine->phone,
                    'medicine' => $medicineName,
                    'days_left' => $days,
                    'response' => $response->json()
                ]);
                
                $results[] = [
                    'patient' => $patientName,
                    'phone' => $medicine->phone,
                    'medicine' => $medicineName,
                    'days_left' => $days,
                    'status' => $response->successful() ? 'sent' : 'failed',
                    'response' => $response->json()
                ];
                
                $totalSent++;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Checked and processed medicine expiry reminders.",
            'total_messages_triggered' => $totalSent,
            'details' => $results
        ]);
    }
}