<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    const PAYMENT_PENDING  = 'pending';
    const PAYMENT_PAID     = 'paid';
    const PAYMENT_FAILED   = 'failed';
    const PAYMENT_EXPIRED  = 'expired';

    const ORDER_PENDING    = 'pending';
    const ORDER_PROCESSING = 'processing';
    const ORDER_SHIPPED    = 'shipped';
    const ORDER_COMPLETED  = 'completed';
    const ORDER_CANCELLED  = 'cancelled';

    // payment_status dan paid_at dikecualikan dari fillable agar hanya bisa diubah
    // lewat markPaymentStatus() dan markAsPaid(), bukan lewat mass assignment.
    protected $fillable = [
        'user_id',
        'order_code',
        'recipient_name',
        'phone',
        'shipping_address',
        'total_amount',
        'order_status',
        'snap_token',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function markPaymentStatus(string $status): void
    {
        $this->forceFill(['payment_status' => $status])->save();
    }

    public function markAsPaid(): void
    {
        $this->forceFill([
            'payment_status' => self::PAYMENT_PAID,
            'paid_at'        => now(),
        ])->save();
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
