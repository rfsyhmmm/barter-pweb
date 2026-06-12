<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Trade;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sapu bersih riwayat transaksi yang sudah deal lebih dari 7 hari
Schedule::call(function () {
    Trade::where('status', 'accepted')
         ->where('updated_at', '<', now()->subDays(7))
         ->delete();
})->daily();