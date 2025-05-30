<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsightSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'insight_id',
        'header',
        'content_markdown',
        'image_filename',
        'background_color',
        'order',
    ];

    public function insight(): BelongsTo
    {
        return $this->belongsTo(Insight::class);
    }
}
