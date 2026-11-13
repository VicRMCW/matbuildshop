<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // Subtotal dihitung on-the-fly; tidak disimpan di DB agar tidak drift dengan harga produk.
    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quantity * ($this->product?->price ?? 0),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
