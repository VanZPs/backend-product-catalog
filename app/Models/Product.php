<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    
    // Product uses product_id (UUID string) as primary key, not id
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'seller_id',
        'product_id',
        'primary_image',
        'name',
        'slug',
        'description',
        'category',
        'category_id',
        'price',
        'stock',
        'images',
        'visitor',
        'is_active',
    ];


    protected $casts = [
        'images'   => 'array', // JSON array
        'price'    => 'decimal:2',
        'visitor'  => 'integer',
    ];

    /**
     * Get the primary image URL
     */
    protected function primaryImage(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if (!$value) return null;
                
                // Remove leading/trailing slashes to normalize
                $value = trim($value, '/');
                
                // If already a full URL, return as-is
                if (str_starts_with($value, 'http')) {
                    return $value;
                }
                
                // Remove 'storage/' prefix if it exists (already included in path)
                if (str_starts_with($value, 'storage/')) {
                    $value = substr($value, 8); // Remove 'storage/'
                }
                
                // Build URL from normalized path
                return url('storage/' . $value);
            },
        );
    }

    /**
     * Get the images array with full URLs
     */
    protected function images(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return [];
                }
                
                // If it's a string (JSON stored as text in DB), decode it
                if (is_string($value)) {
                    try {
                        $decoded = json_decode($value, true);
                        if (!is_array($decoded)) {
                            return [];
                        }
                        $value = $decoded;
                    } catch (\Exception $e) {
                        return [];
                    }
                }
                
                // If still not an array, return empty
                if (!is_array($value)) {
                    return [];
                }
                
                // Transform each image path to full URL
                return array_map(function ($image) {
                    // Remove leading/trailing slashes to normalize
                    $image = trim($image, '/');
                    
                    if (str_starts_with($image, 'http')) {
                        return $image;
                    }
                    
                    // Remove 'storage/' prefix if it exists (already included in path)
                    if (str_starts_with($image, 'storage/')) {
                        $image = substr($image, 8); // Remove 'storage/'
                    }
                    
                    // Build URL from normalized path
                    return url('storage/' . $image);
                }, $value);
            },
        );
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->product_id)) {
                $model->product_id = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'product_id';
    }
}
