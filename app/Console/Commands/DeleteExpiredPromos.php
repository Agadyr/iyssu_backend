<?php

namespace App\Console\Commands;

use App\Models\Promocode;
use Carbon\Traits\Date;
use Illuminate\Console\Command;

class DeleteExpiredPromos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление всех промокод которые не дейтсвительны';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $now = \Illuminate\Support\Facades\Date::now();

        $deletedCount = Promocode::where('expires_at', '<', $now)->delete();

        if ($deletedCount > 0) {
            $this->info("Удалено истекших промокодов: $deletedCount");
        } else {
            $this->info('Не найдено истёкших промокодов');
        }
    }
}
