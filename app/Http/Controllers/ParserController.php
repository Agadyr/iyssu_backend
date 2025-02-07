<?php

namespace App\Http\Controllers;

use App\Service\ParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ParserController extends Controller
{
    protected  ParserService $parserService;
    public function __construct(ParserService $parser)
    {
        $this->parserService = $parser;
    }

    public function getExchanges(): JsonResponse
    {
        $data = $this->parserService->parseExchangeRates();
        return response()->json($data);
    }
}

