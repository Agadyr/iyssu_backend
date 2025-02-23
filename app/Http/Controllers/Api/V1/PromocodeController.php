<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Promocode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PromocodeController extends Controller
{
    private $pattern = '/PARTNER-[A-Za-z0-9]+/';

    public function index(Request $request): JsonResponse
    {
        $promo = $request->query('promo');

        if (!$promo) {
            return response()->json(['error' => 'Promo code is required'], 400);
        }

        $data = Promocode::isValid($promo);
        if (!$data) {
            return response()->json(['error' => 'Invalid or expired promo code'], 400);
        }

        $expires = Date::now()->diff($data['expires_at']);

        return response()->json([
            'promo' => "Ваш промокод {$data->code}",
            'discount' => "Имеет {$data->discount}% скидку",
            'expires_date' => "истекает через {$expires->format('%d')} дней и {$expires->format('%h')} часов"
        ]);
    }

    public function store()
    {
        // Логика создания промокода
    }

    public function delete()
    {
        // Логика удаления промокода
    }

    public function putOther()
    {
        return [
            "codes" => ["PARTNER-123456", "PARTNER-654321", "RANDOM-777","PARTNER-///////"]
        ];
    }

    public function checkServerPromos()
    {
        $response = app()->call('App\\Http\\Controllers\\Api\\V1\\PromocodeController@putOther');

        $clear = [];

        foreach ($response['codes'] as $item) {
            if (preg_match($this->pattern, $item, $matches)) {
                $clear[] = $item;
            }
        }

        $existingCodes = Promocode::whereIn('code', $clear)->pluck('code')->toArray();

        $newCodes = array_diff($clear, $existingCodes);

        if (!empty($newCodes)) {
            $insertData = array_map(fn($code) => ['code' => $code,'discount' => 10, 'expires_at' => now()->addDay() , 'created_at' => now(), 'updated_at' => now()], $newCodes);
            Promocode::insert($insertData);
        }
        $result = Promocode::whereIn('code', $clear)->get();

        return response()->json($result);
    }
}
