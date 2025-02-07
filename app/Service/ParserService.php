<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class ParserService
{
    public function parseExchangeRates(): array
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'])
            ->get('https://mamyexchange.com/');

        $html = $response->body();

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        $rows = $xpath->query("//div[contains(@class, 'er-list-row')]");
        $data = [];

        foreach ($rows as $row) {
            $img = $xpath->query(".//img", $row);
            $currency = $img->length ? trim($img[0]->getAttribute('alt')) : 'NOT';

            $spans = $xpath->query(".//span", $row);

            if ($spans->length < 3) {
                continue;
            }

            $denomination = trim($spans[1]->textContent); // Номинал
            $buying = trim($spans[2]->textContent); // Курс покупки
            $selling = trim($spans[3]->textContent); // Курс продажи

            $data[] = compact('currency', 'denomination', 'buying', 'selling');
        }
        return $data;
    }
}
