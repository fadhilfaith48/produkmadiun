<?php
// ================================================================
// FILE: app/Models/User.php - VERSI FINAL
// ================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',           // 'admin', 'umkm', 'customer'
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ===============================
    // RELASI
    // ===============================
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ===============================
    // ROLE HELPERS
    // ===============================
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUmkm(): bool
    {
        return $this->role === 'umkm';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer' || !$this->role;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ===============================
    // SCOPES
    // ===============================
    public function scopeUmkm($query)
    {
        return $query->where('role', 'umkm');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}