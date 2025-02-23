<?php

namespace App\Console\Commands;

use App\Models\Promocode;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GeneratePromoCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:generate {discount} {--expires=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерирует новый промокод с заданной скидкой';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $discount = $this->argument('discount');
        $date = $this->option('expires') ?? [24];

        $code = strtoupper(Str::random(10));
        $expiresAt = now()->addHours((int)$date);
        Promocode::create([
            'code' => $code,
            'discount' => $discount,
            'expires_at' => $expiresAt
        ]);

        $this->info("Промокод $code со скидкой $discount на $expiresAt время был сгенирирован ");
    }
}
