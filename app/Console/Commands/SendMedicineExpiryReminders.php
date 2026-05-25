<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientMedicine;
use App\Models\Patient;
use App\Mail\MedicineExpiryReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendMedicineExpiryReminders extends Command
{
    protected $signature = 'medicines:remind-expiry 
                            {--days=5 : Days before expiry to send reminder (5, 2, or 1)}
                            {--dry-run : Show what would be sent without sending emails}';

    protected $description = 'Send medicine expiry reminder emails to patients';

    public function handle()
    {
        $daysOptions = [5, 2, 1];
        $dryRun = $this->option('dry-run');
        
        foreach ($daysOptions as $daysLeft) {
            $this->sendRemindersForDays($daysLeft, $dryRun);
        }
        
        $this->info('Medicine expiry reminder job completed.');
        return 0;
    }

   private function sendRemindersForDays($daysLeft, $dryRun)
{
    $targetDate = Carbon::today()->addDays($daysLeft);
    
    // Find medicines expiring on target date
    $expiringMedicines = PatientMedicine::with(['patient', 'medicine'])
        ->where('is_active', true)
        ->whereDate('end_date', $targetDate)
        ->whereNotNull('end_date')
        ->get();

    if ($expiringMedicines->isEmpty()) {
        $this->info("No medicines expiring in {$daysLeft} days.");
        return;
    }

    // Group by patient_id
    $groupedByPatient = $expiringMedicines->groupBy('patient_id');

    foreach ($groupedByPatient as $patientId => $medicines) {
        // ✅ FIXED: Patient explicitly fetch karo with null check
        $patient = Patient::find($patientId);
        
        if (!$patient) {
            $this->warn("⚠️ Patient ID {$patientId} not found. Skipping " . $medicines->count() . " medicine(s).");
            continue;
        }
        
        // Skip if no email
        if (empty($patient->email)) {
            $this->warn("⚠️ Patient {$patient->first_name} {$patient->last_name} (ID: {$patient->id}) has no email. Skipping.");
            continue;
        }

        // Check if already sent reminder for this date (avoid duplicates)
        if (class_exists(\App\Models\ReminderLog::class)) {
            $alreadySent = $patient->reminderLogs()
                ->where('type', 'medicine_expiry')
                ->where('days_before', $daysLeft)
                ->whereDate('sent_at', Carbon::today())
                ->exists();

            if ($alreadySent) {
                $this->info("✓ Reminder already sent to {$patient->email} for {$daysLeft} days.");
                continue;
            }
        }

        // Format medicines list
        $medicineList = $medicines->map(function($pm) {
            return [
                'name' => $pm->medicine?->name ?? 'Unknown Medicine',
                'dosage' => $pm->dosage,
                'quantity' => $pm->quantity,
                'end_date' => $pm->end_date,
            ];
        })->toArray();

        if ($dryRun) {
            $this->info("[DRY RUN] Would send to {$patient->email}: {$medicineList[0]['name']} expires in {$daysLeft} days");
            continue;
        }

        try {
            Mail::to($patient->email)->send(new \App\Mail\MedicineExpiryReminder($patient, $medicineList, $daysLeft));
            
            // Log the sent reminder (if ReminderLog model exists)
            if (class_exists(\App\Models\ReminderLog::class)) {
                $patient->reminderLogs()->create([
                    'type' => 'medicine_expiry',
                    'days_before' => $daysLeft,
                    'sent_at' => now(),
                    'meta' => json_encode(['medicine_count' => count($medicineList)]),
                ]);
            }
            
            $this->info("✓ Sent reminder to {$patient->email} ({$daysLeft} days before expiry)");
            
        } catch (\Exception $e) {
            Log::error('Medicine Reminder Failed', [
                'patient_id' => $patient->id,
                'email' => $patient->email,
                'error' => $e->getMessage()
            ]);
            $this->error("✗ Failed to send to {$patient->email}: " . $e->getMessage());
        }
    }
}
}