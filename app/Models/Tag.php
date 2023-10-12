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

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
