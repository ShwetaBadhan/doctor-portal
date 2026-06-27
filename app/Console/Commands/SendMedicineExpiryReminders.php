<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientMedicine;
use App\Models\Patient;
use App\Mail\MedicineExpiryReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendMedicineExpiryReminders extends Command
{
    protected $signature = 'medicines:remind-expiry 
                            {--days=5 : Days before expiry to send reminder (5, 2, or 1)}
                            {--dry-run : Show what would be sent without sending}
                            {--email-only : Send only email reminders}
                            {--whatsapp-only : Send only WhatsApp reminders}';

    protected $description = 'Send medicine expiry reminder emails and WhatsApp messages to patients';

    public function handle()
    {
        $daysOptions = [10, 7, 5, 3, 2];
        $dryRun = $this->option('dry-run');
        $emailOnly = $this->option('email-only');
        $whatsappOnly = $this->option('whatsapp-only');
        
        $this->info('🚀 Starting medicine expiry reminders...');
        
        foreach ($daysOptions as $daysLeft) {
            $this->sendRemindersForDays($daysLeft, $dryRun, $emailOnly, $whatsappOnly);
        }
        
        $this->info('✅ Medicine expiry reminder job completed.');
        return 0;
    }

    private function sendRemindersForDays($daysLeft, $dryRun, $emailOnly, $whatsappOnly)
    {
        $targetDate = Carbon::today()->addDays($daysLeft);
        
        $expiringMedicines = PatientMedicine::with(['patient', 'medicine'])
            ->where('is_active', true)
            ->whereDate('end_date', $targetDate)
            ->whereNotNull('end_date')
            ->get();

        if ($expiringMedicines->isEmpty()) {
            $this->info("ℹ️  No medicines expiring in {$daysLeft} days.");
            return;
        }

        $groupedByPatient = $expiringMedicines->groupBy('patient_id');

        foreach ($groupedByPatient as $patientId => $medicines) {
            $patient = Patient::find($patientId);
            
            if (!$patient) {
                $this->warn("⚠️  Patient ID {$patientId} not found. Skipping.");
                continue;
            }

            // ✅ Check duplicate using ReminderLog
            if (class_exists(\App\Models\ReminderLog::class)) {
                $alreadySent = $patient->reminderLogs()
                    ->where('type', 'medicine_expiry')
                    ->where('days_before', $daysLeft)
                    ->whereDate('sent_at', Carbon::today())
                    ->exists();

                if ($alreadySent) {
                    $this->info("✓ Already reminded {$patient->first_name} for {$daysLeft} days. Skipping.");
                    continue;
                }
            }

            $medicineCount = $medicines->count();
            
            if ($dryRun) {
                $this->info("[DRY RUN] Would send to {$patient->first_name} ({$medicineCount} medicines, {$daysLeft} days left)");
                continue;
            }

            // ✅ SEND EMAIL (if not whatsapp-only)
            if (!$whatsappOnly && !empty($patient->email)) {
                $this->sendEmailReminder($patient, $medicines, $daysLeft);
            }

            // ✅ SEND WHATSAPP (if not email-only)
            if (!$emailOnly && !empty($patient->phone)) {
                $this->sendWhatsAppReminder($patient, $daysLeft);
            }

            // ✅ Log the reminder
            if (class_exists(\App\Models\ReminderLog::class)) {
                $patient->reminderLogs()->create([
                    'type' => 'medicine_expiry',
                    'days_before' => $daysLeft,
                    'sent_at' => now(),
                    'meta' => json_encode([
                        'medicine_count' => $medicineCount,
                        'email_sent' => !$whatsappOnly && !empty($patient->email),
                        'whatsapp_sent' => !$emailOnly && !empty($patient->phone),
                    ]),
                ]);
            }
        }
    }

    /**
     * Send Email Reminder
     */
    private function sendEmailReminder($patient, $medicines, $daysLeft)
    {
        try {
            $medicineList = $medicines->map(function($pm) {
                return [
                    'name' => $pm->custom_name ?? ($pm->medicine?->name ?? 'Unknown Medicine'),
                    'dosage' => $pm->dosage,
                    'quantity' => $pm->quantity,
                    'end_date' => $pm->end_date,
                ];
            })->toArray();

            Mail::to($patient->email)->send(new \App\Mail\MedicineExpiryReminder($patient, $medicineList, $daysLeft));
            
            $this->info("📧 Email sent to {$patient->email} ({$daysLeft} days)");
            
        } catch (\Exception $e) {
            Log::error('Medicine Email Reminder Failed', [
                'patient_id' => $patient->id,
                'email' => $patient->email,
                'error' => $e->getMessage()
            ]);
            $this->error("✗ Email failed for {$patient->email}: " . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp Reminder
     */
    private function sendWhatsAppReminder($patient, $daysLeft)
    {
        try {
            $baseUrl = env('MYOPERATOR_BASE_URL');
            $apiKey = env('MYOPERATOR_API_KEY');
            $companyId = env('MYOPERATOR_COMPANY_ID');
            $phoneNumberId = env('MYOPERATOR_PHONE_NUMBER_ID');

            if (!$baseUrl || !$apiKey || !$phoneNumberId) {
                $this->warn("⚠️  WhatsApp config missing. Skipping WhatsApp for {$patient->first_name}");
                return;
            }

            // ✅ Parse phone number using libphonenumber
            $phoneData = $this->parsePhoneNumber(
                $patient->phone, 
                $patient->phone_country_iso ?? 'IN'
            );
            
            $countryCode = $phoneData['country_code'];
            $phone = $phoneData['local_number'];

            $patientName = trim($patient->first_name . ' ' . $patient->last_name);
            
            // ✅ Simple template - sirf patient name
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
                        'template_name' => 'medicine_expiry',
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

            if ($response->successful()) {
                $this->info("📱 WhatsApp sent to {$patient->phone} ({$daysLeft} days)");
            } else {
                $this->warn("⚠️  WhatsApp failed for {$patient->phone}: " . $response->body());
            }

            Log::info("WhatsApp Medicine Reminder", [
                'patient' => $patientName,
                'phone' => $patient->phone,
                'days_left' => $daysLeft,
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body()
            ]);
            
        } catch (\Exception $e) {
            Log::error('WhatsApp Medicine Reminder Failed', [
                'patient_id' => $patient->id,
                'phone' => $patient->phone,
                'error' => $e->getMessage()
            ]);
            $this->error("✗ WhatsApp failed for {$patient->phone}: " . $e->getMessage());
        }
    }

    /**
     * Parse phone number using libphonenumber
     */
    private function parsePhoneNumber($fullPhone, $defaultCountry = 'IN')
    {
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $phoneNumber = $phoneUtil->parse($fullPhone, $defaultCountry);
            
            return [
                'country_code' => (string)$phoneNumber->getCountryCode(),
                'local_number' => (string)$phoneNumber->getNationalNumber(),
                'valid' => $phoneUtil->isValidNumber($phoneNumber)
            ];
        } catch (\Exception $e) {
            // Fallback
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
}