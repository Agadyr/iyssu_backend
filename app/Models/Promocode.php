<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Promocode extends Model
{
    protected $fillable = [
        'discount',
        'code',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    public static function isValid(string $promo): ?self
    {
        return self::where('code', $promo)
            ->where('expires_at', '>', Date::now())
            ->first();
    }
}
