<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParserController;

Route::get('/get/currents', [ParserController::class, 'getExchanges']);
