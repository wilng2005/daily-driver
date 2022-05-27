<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Capture extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Actionable;

    public function prefix_with_title(){
        return $this->prefix().$this->name;
    }

    public function prefix(){
        return $this->capture?$this->capture->prefix_with_title()."/":"";
    }     

    public function captures(){
        return $this->hasMany(Capture::class);
    }

    public function capture(){
        return $this->belongsTo(Capture::class);
    }

    public function remove_from_inbox(){
        $this->inbox = false;
        $this->save();
    }
}
