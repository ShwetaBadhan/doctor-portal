<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class WhatsAppController extends Controller
{
    /**
     * Parse phone number using libphonenumber
     * Automatically detects country code from phone number
     */
    private function parsePhoneNumber($fullPhone, $defaultCountry = 'IN')
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            
            // Parse the phone number
            $phoneNumber = $phoneUtil->parse($fullPhone, $defaultCountry);
            
            // Get country code and national number
            $countryCode = $phoneNumber->getCountryCode();
            $nationalNumber = $phoneNumber->getNationalNumber();
            
            return [
                'country_code' => (string)$countryCode,
                'local_number' => (string)$nationalNumber,
                'valid' => $phoneUtil->isValidNumber($phoneNumber)
            ];
        } catch (\Exception $e) {
            // Fallback: manual extraction
            $phone = preg_replace('/[^0-9]/', '', $fullPhone);
            
            if (strlen($phone) <= 10) {
                return [
                    'country_code' => '91',
                    'local_number' => $phone,
                    'valid' => false
                ];
            }
            
            return [
                'country_code' => substr($phone, 0, 2),
                'local_number' => substr($phone, 2),
                'valid' => false
            ];
        }
    }

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
        $apiKey = env('MYOPERATOR_API_KEY');
        $companyId = env('MYOPERATOR_COMPANY_ID');
        $phoneNumberId = env('MYOPERATOR_PHONE_NUMBER_ID');

        // ✅ Parse phone number using libphonenumber
        $phoneData = $this->parsePhoneNumber(
            $patient->phone, 
            $patient->phone_country_iso ?? 'IN'
        );
        
        $countryCode = $phoneData['country_code'];
        $phone = $phoneData['local_number'];

        $patientName = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));

        $url = $baseUrl . '/chat/messages';

        $payload = [
            'phone_number_id' => $phoneNumberId,
            'customer_country_code' => $countryCode,
            'customer_number' => $phone, 
            'data' => [
                'type' => 'template',
                'context' => [
                    'template_name' => 'welcome_msg',
                    'body' => [
                        '2' => $patientName
                    ]
                ]
            ]
        ];

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'X-MYOP-COMPANY-ID' => $companyId,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        Log::info('Welcome Letter Sent', [
            'patient_id' => $patientId,
            'patient_name' => $patientName,
            'phone' => $phone,
            'country_code' => $countryCode,
            'valid' => $phoneData['valid'],
            'status_code' => $response->status(),
            'response' => $response->json() ?? $response->body()
        ]);

        return response()->json([
            'status' => $response->successful() ? 'success' : 'error',
            'response' => $response->json() ?? $response->body(),
            'status_code' => $response->status()
        ]);
    }

    /**
     * Automated Medicine Expiry Reminders
     *//**
 * Automated Medicine Expiry Reminders
 */
public function sendExpiryReminders()
{
    $baseUrl = env('MYOPERATOR_BASE_URL');
    $apiKey = env('MYOPERATOR_API_KEY');
    $companyId = env('MYOPERATOR_COMPANY_ID');
    $phoneNumberId = env('MYOPERATOR_PHONE_NUMBER_ID');

    $remindDays = [10, 7, 5, 3, 2];
    $today = Carbon::today();

    $totalSent = 0;
    $results = [];
    $sentPatients = []; // ✅ Track patients who already received message

    foreach ($remindDays as $days) {
        $targetDate = $today->copy()->addDays($days);
        
        // ✅ Distinct patients fetch karo - ek patient ek baar hi aaye
        $patients = DB::table('patients')
            ->join('patient_medicines', 'patients.id', '=', 'patient_medicines.patient_id')
            ->whereDate('patient_medicines.end_date', $targetDate)
            ->where('patient_medicines.is_active', 1)
            ->select(
                'patients.id as patient_id',
                'patients.first_name',
                'patients.last_name',
                'patients.phone',
                'patients.phone_country_iso'
            )
            ->distinct()
            ->get();

        foreach ($patients as $patient) {
            // ✅ Check if already sent message to this patient
            $patientKey = $patient->patient_id . '_' . $days;
            if (isset($sentPatients[$patientKey])) {
                continue; // Skip - already sent
            }

            // ✅ Parse phone number
            $phoneData = $this->parsePhoneNumber(
                $patient->phone, 
                $patient->phone_country_iso ?? 'IN'
            );
            
            $countryCode = $phoneData['country_code'];
            $phone = $phoneData['local_number'];

            $patientName = trim($patient->first_name . ' ' . $patient->last_name);
            
          
            $bodyParams = [
                '1' => $patientName
            ];

            $payload = [
                'phone_number_id' => $phoneNumberId,
                'customer_country_code' => $countryCode,
                'customer_number' => $phone, 
                'data' => [
                    'type' => 'template',
                    'context' => [
                        'template_name' => 'medicine_expiry_reminder', 
                        'body' => $bodyParams,
                    ],
                ],
            ];

            $url = $baseUrl . '/chat/messages';

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'X-MYOP-COMPANY-ID' => $companyId,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);
            
            Log::info("Medicine reminder sent", [
                'patient' => $patientName,
                'patient_id' => $patient->patient_id,
                'phone' => $patient->phone,
                'formatted_phone' => $phone,
                'country_code' => $countryCode,
                'valid' => $phoneData['valid'],
                'days_left' => $days,
                'status_code' => $response->status(),
                'response' => $response->json() ?? $response->body()
            ]);
            
            $results[] = [
                'patient' => $patientName,
                'patient_id' => $patient->patient_id,
                'phone' => $patient->phone,
                'days_left' => $days,
                'status' => $response->successful() ? 'sent' : 'failed',
                'response' => $response->json() ?? $response->body()
            ];
            
            $totalSent++;
            
            // ✅ Mark as sent
            $sentPatients[$patientKey] = true;
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