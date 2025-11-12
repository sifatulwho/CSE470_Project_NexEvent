<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Commands
|--------------------------------------------------------------------------
|
| Here you can define all of your custom Artisan commands.
| Each command closure receives the command instance allowing
| for simple interaction and output formatting.
|
*/

Artisan::command('inspire', function () {
    $quote = Inspiring::quote();
    $this->comment($quote);
})->purpose('Display an inspiring quote');
