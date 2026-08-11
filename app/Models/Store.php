<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'slug',
        'description',
        'address',
        'village',
        'district',
        'phone',
        'whatsapp',
        'logo',
        'banner',
        'is_verified',
        'is_active',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active'   => 'boolean',
    ];

    // ===============================
    // RELASI
    // ===============================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    // ===============================
    // ACCESSORS
    // ===============================
    public function getWhatsappLinkAttribute()
    {
        if (!$this->whatsapp) return null;

        $phone = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return 'https://wa.me/' . $phone;
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->is_active) return 'Tidak Aktif';
        if (!$this->is_verified) return 'Menunggu Verifikasi';
        return 'Terverifikasi';
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = $store->generateUniqueSlug($store->store_name);
            }
        });
    }

    // Buat slug unik (cek duplikat di DB)
    public static function generateUniqueSlug($name, $ignoreId = null)
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . ++$i;
        }

        return $slug;
    }
}