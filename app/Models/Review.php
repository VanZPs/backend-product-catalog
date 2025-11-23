<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Laravolt\Indonesia\Models\Provinsi;

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

    /**
     * Casts for attributes.
     * Ensure rating is treated as integer when serializing.
     */
    protected $casts = [
        'rating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function province()
    {
        return $this->belongsTo(Provinsi::class, 'province_id');
    }

    /**
     * Return a primitive snapshot of the review suitable for notifications
     * (safe to serialize for queued jobs).
     *
     * @return array<string,mixed>
     */
    public function toSnapshot(): array
    {
        $productName = null;
        if ($this->relationLoaded('product')) {
            $productName = $this->product ? $this->product->name : null;
        } else {
            // attempt to get product name without forcing heavy operations
            try {
                $productName = $this->product ? $this->product->name : null;
            } catch (\Throwable $e) {
                $productName = null;
            }
        }

        return [
            'review_id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $productName,
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating !== null ? (int) $this->rating : null,
            'comment' => $this->comment,
            'province_id' => $this->province_id,
        ];
    }
}
