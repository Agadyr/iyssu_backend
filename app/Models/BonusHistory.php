<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'operation_type', 'description',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('orderByCreatedAt', static function (Builder $query) {
            $query->orderByDesc('created_at');
        });
    }
}
