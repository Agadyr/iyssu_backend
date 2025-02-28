<?php

namespace App\Models;

    use Database\Factories\UserFactory;
    use Exception;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
    use Illuminate\Support\Facades\Auth;
    use Laravel\Sanctum\HasApiTokens;
    use TypeError;

    class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'city'
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bonusPoints(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BonusPoints::class);
    }

    public function bonusHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonusHistory::class);
    }

    /**
     * @throws Exception
     */

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    }
