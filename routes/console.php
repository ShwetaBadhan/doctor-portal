<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\WhatsAppController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Run every day at 9:00 AM
Schedule::call(function () {
    $controller = new WhatsAppController();
    $controller->sendExpiryReminders();
})->dailyAt('09:00');