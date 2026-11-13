<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'gross_amount',
        'raw_notification',
        'transaction_time',
    ];

    protected function casts(): array
    {
        return [
            // JSON disimpan agar notifikasi Midtrans bisa di-debug tanpa kehilangan data asli.
            'raw_notification' => 'array',
            'transaction_time' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
