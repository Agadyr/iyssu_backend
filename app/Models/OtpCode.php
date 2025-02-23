<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['email', 'code', 'expires_at', 'user_id', 'type'];

    protected $dates = [
        'expires_at',
    ];

}
