<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_whatsapp',
        'customer_address',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'notes',
        'payment_method',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public const STATUSES = [
        'pending',
        'confirmed',
        'processing',
        'shipped',
        'completed',
        'cancelled',
    ];

    public const STATUS_TRANSITIONS = [
        'pending'    => ['confirmed', 'cancelled'],
        'confirmed'  => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped'    => ['completed'],
        'completed'  => [],
        'cancelled'  => [],
    ];

    // ===============================
    // RELASI
    // ===============================
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status helpers
    public function getStatusLabelAttribute()
    {
        return self::statusLabel($this->status ?? 'pending');
    }

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Menunggu Pembayaran',
            'confirmed' => 'Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function allowedNextStatuses(): array
    {
        return self::STATUS_TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, $this->allowedNextStatuses(), true);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info', 
            'processing' => 'primary',
            'shipped' => 'secondary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    // Order code generator
    public static function generateCode()
    {
        $code = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(8));
        while (self::where('order_code', $code)->exists()) {
            $code = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(8));
        }
        return $code;
    }
}