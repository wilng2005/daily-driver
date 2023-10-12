<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Nova\Actions\Actionable;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Actionable;

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
