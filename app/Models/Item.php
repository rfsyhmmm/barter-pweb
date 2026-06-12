<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    // Mengizinkan pengisian data (mass assignment)
    protected $guarded = [];

    /**
     * Relasi: Item ini dimiliki oleh siapa?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}