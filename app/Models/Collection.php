<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'discount_percent', 'is_published'];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getOriginalTotalAttribute(): float
    {
        return (float) $this->photos->sum('price');
    }

    public function getDiscountedTotalAttribute(): float
    {
        $total = $this->getOriginalTotalAttribute();
        return round($total * (1 - $this->discount_percent / 100), 2);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
