<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'name_es', 'name_en', 'description_es', 'description_en'])]
class Category extends Model
{
    use HasFactory;

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'es' ? $this->name_es : $this->name_en;
    }

    public function localizedDescription(): ?string
    {
        return app()->getLocale() === 'es' ? $this->description_es : $this->description_en;
    }
}
