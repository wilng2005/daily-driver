<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Actionable;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function tags(): BelongsToMany
    {
        //@codeCoverageIgnoreStart
        return $this->belongsToMany(Tag::class);
        //@codeCoverageIgnoreEnd
    }
}
