<?php

namespace App\Console;

use App\Console\Commands\ParseExchangeRates;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Регистрация команд консоли.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        $this->commands([
            ParseExchangeRates::class,
        ]);
    }
}
