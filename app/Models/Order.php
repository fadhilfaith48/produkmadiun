<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $status = $this->status ?? 'pending';
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
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}