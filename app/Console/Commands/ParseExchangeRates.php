<?php

namespace App\Console\Commands;

use App\Service\ParserService;
use Illuminate\Console\Command;

class ParseExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:exchangerates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсит курсы валют с сайта MamyExchange и выводит результат';

    /**
     * Execute the console command.
     */
    protected ParserService $parserService;
    public function __construct(ParserService $parserService)
    {
        parent::__construct();
        $this->parserService = $parserService;
    }

    public function handle(): void
    {
        $this->info('Парсинг курсов валют...');
        $exchangeRates = $this->parserService->parseExchangeRates();

        if (empty($exchangeRates)) {
            $this->error('Не удалось получить курсы валют!');
        } else {
            $this->info('Курсы валют успешно получены:');
            foreach ($exchangeRates as $rate) {
                $this->line("Валюта: {$rate['currency']} | Номинал: {$rate['denomination']} | Покупка: {$rate['buying']} | Продажа: {$rate['selling']}");
            }
        }
    }
}
