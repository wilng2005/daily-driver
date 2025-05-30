<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Str;

class Insight extends Model
{
    /**
     * Scope a query to only include published insights.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($insight) {
            if (empty($insight->slug) && !empty($insight->title)) {
                $insight->slug = Str::slug($insight->title);
            }
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'image_path',
        'description',
        'keywords',
        'published_at',
    ];

    protected $casts = [
        'keywords' => 'array',
        'published_at' => 'datetime',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(InsightSection::class);
    }
}
