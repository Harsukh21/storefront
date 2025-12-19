<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Clear expired cache entries daily
Schedule::command('cache:clear')->daily()->at('02:00');

// Clear expired sessions weekly
Schedule::command('session:gc')->weekly();
