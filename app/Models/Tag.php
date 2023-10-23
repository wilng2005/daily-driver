<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Nova\Actions\Actionable;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Actionable;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function posts(): BelongsToMany
    {
        //@codeCoverageIgnoreStart
        return $this->belongsToMany(Post::class);
        //@codeCoverageIgnoreEnd
    }
}
