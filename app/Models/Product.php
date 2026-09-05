<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id', 'category_id', 'name', 'slug',
        'description', 'price', 'stock', 'unit',
        'image', 'weight', 'is_active', 'views',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function scopeAvailableForPublic($query)
    {
        return $query->where('is_active', true)
            ->whereHas('store', fn($q) => $q->where('is_verified', true)->where('is_active', true));
    }

    public function getAverageRatingAttribute()
    {
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return (float) ($this->attributes['reviews_avg_rating'] ?? 0);
        }

        if ($this->relationLoaded('reviews')) {
            return (float) ($this->reviews->avg('rating') ?? 0);
        }

        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getWhatsappOrderLink($qty = 1)
    {
        $phone = preg_replace('/[^0-9]/', '', $this->store->whatsapp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $total   = number_format($this->price * $qty, 0, ',', '.');
        $message = "Halo, saya ingin memesan:\n\n";
        $message .= "Produk : {$this->name}\n";
        $message .= "Jumlah : {$qty} {$this->unit}\n";
        $message .= "Total  : Rp {$total}\n\n";
        $message .= "Mohon konfirmasinya. Terima kasih!";

        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }
}