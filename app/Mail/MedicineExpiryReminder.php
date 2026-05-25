<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicineExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $expiringMedicines;
    public $daysLeft;

    public function __construct($patient, $expiringMedicines, $daysLeft)
    {
        $this->patient = $patient;
        $this->expiringMedicines = $expiringMedicines;
        $this->daysLeft = $daysLeft;
    }

    public function build()
    {
        return $this->subject("⚠️ Medicine Expiry Reminder - {$this->daysLeft} Days Left")
                    ->view('pages.emails.medicine-expiry-reminder')
                    ->with([
                        'patient' => $this->patient,
                        'medicines' => $this->expiringMedicines,
                        'daysLeft' => $this->daysLeft,
                    ]);
    }
}