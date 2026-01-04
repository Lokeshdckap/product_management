<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'stock',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}


 

