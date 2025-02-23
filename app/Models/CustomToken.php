<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'type',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
