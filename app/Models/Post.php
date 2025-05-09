<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use Laravel\Scout\Searchable;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'source',
        'ai_prompt',
        'published_at',
    ];
    use HasFactory;
    use SoftDeletes;
    use Actionable;
    use Searchable;

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

    //@codeCoverageIgnoreStart
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
    //@codeCoverageIgnoreEnd
}
