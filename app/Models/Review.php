<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'province_id',
        'rating',
        'comment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function province()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Provinsi::class, 'province_id');
    }
}
