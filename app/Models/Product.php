<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'brand', 'quantity', 'unit', 'is_new', 'category_id',
        'volume_options', 'scent', 'image_url', 'rating', 'discount', 'brand_url'
    ];

    protected $casts = [
        'volume_options' => 'array',
        'scent' => 'array',
        'image_url' => 'array',
        'brand_url' => 'array',
        'is_new' => 'boolean',
    ];

    public $hidden = [
        'search_vector', 'updated_at'
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

}
