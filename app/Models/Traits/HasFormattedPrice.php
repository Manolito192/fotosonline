<?php

namespace App\Models\Traits;

trait HasFormattedPrice
{
    public function getFormattedPriceAttribute(): string
    {
        return config('store.currency.symbol') . number_format($this->price, 2, '.', ',');
    }
}
