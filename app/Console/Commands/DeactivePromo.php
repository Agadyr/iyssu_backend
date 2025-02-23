<?php

namespace App\Console\Commands;

use App\Models\Promocode;
use Illuminate\Console\Command;

class DeactivePromo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:deactivate {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Делаем недействительным промокод';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $promo = $this->argument('code');
        $data = Promocode::isValid($promo);

        if (!$data) {
            $this->info('Нету такого промокода');
        } else {
            $data->is_active = true;
            $data->save();
            $this->info("Промокод $promo был деактивирован");
        }
    }
}
