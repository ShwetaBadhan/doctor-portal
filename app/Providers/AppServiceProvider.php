<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Patient registration par automatically welcome message bhejein
        \Illuminate\Support\Facades\Event::listen('eloquent.created: App\Models\Patient', function ($patient) {
            $this->sendWelcomeMessage($patient);
        });
    }

    private function sendWelcomeMessage($patient)
    {
        try {
            $baseUrl = env('MYOPERATOR_BASE_URL');
            
            // ✅ SAHI KEY USE KAREIN - WABA API KEY
            $wabaApiKey = env('MYOPERATOR_WABA_API_KEY'); // 27199918376314761

            $phone = $patient->phone ?? $patient['phone'];
            $phone = preg_replace('/[^0-9]/', '', $phone);
            
            $countryCode = '91';
            if (strlen($phone) > 10) {
                $countryCode = substr($phone, 0, 2);
                $phone = substr($phone, 2);
            }

            $patientName = '';
            if (isset($patient->first_name)) {
                $patientName = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));
            } else {
                $patientName = $patient->name ?? 'Patient';
            }

            $url = $baseUrl . '/send-message';

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

            // ✅ Actual API call with SAHI key
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'x-api-key' => $wabaApiKey, // ✅ WABA KEY
                ])
                ->post($url, $payload);

            Log::info("Welcome message sent", [
                'patient' => $patientName,
                'phone' => $phone,
                'waba_key_used' => $wabaApiKey,
                'url' => $url,
                'response' => $response->json() ?? $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error("Welcome message failed: " . $e->getMessage());
        }
    }
}