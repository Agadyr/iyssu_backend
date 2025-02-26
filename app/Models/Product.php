<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'brand', 'quantity_ml', 'is_new', 'category_id',
        'volume_options', 'scent', 'scent_type', 'image_url', 'rating', 'discount'
    ];

    protected $casts = [
        'volume_options' => 'array',
        'scent' => 'array',
        'scent_type' => 'array',
        'image_url' => 'array',
        'is_new' => 'boolean',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
