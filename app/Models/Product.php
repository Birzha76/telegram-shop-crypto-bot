<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'details',
    ];

    public function category()
    {
        return $this->hasOne(Category::class);
    }
}
