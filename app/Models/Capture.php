<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Actionable;
use Laravel\Scout\Searchable;

class Capture extends Model
{
    protected $fillable = [
        'name', 'content', 'priority_no', 'inbox', 'next_action', 'ai_delay_suggestion'
    ];

    use HasFactory;
    use SoftDeletes;
    use Actionable;
    use Searchable;

    public function prefix_with_title()
    {
        return $this->prefix().$this->name;
    }

    public function prefix()
    {
        return $this->capture ? $this->capture->prefix_with_title().'/' : '';
    }

    public function captures()
    {
        return $this->hasMany(Capture::class);
    }

    public function capture()
    {
        return $this->belongsTo(Capture::class);
    }

    // @codeCoverageIgnoreStart
    public function remove_from_inbox()
    {
        $this->inbox = false;
        $this->save();
    }

    public function remove_from_next_action()
    {
        $this->next_action = false;
        $this->save();
    }

    public function add_to_inbox()
    {
        $this->inbox = true;
        $this->next_action = false;
        $this->save();
    }

    public function add_to_next_action()
    {
        $this->inbox = false;
        $this->next_action = true;
        $this->save();
    }

    public function daily_schedule()
    {

        $this->add_daily_task_to_inbox();
        $this->add_scheduled_task_to_inbox();

        if ($this->inbox || $this->next_action) {
            $this->refresh_priority_no();
        }
    }

    public function weekday_schedule()
    {
        $this->add_weekday_task_to_inbox();
    }
    // @codeCoverageIgnoreEnd

    public function add_daily_task_to_inbox()
    {
        if (Str::startsWith($this->name, ['Daily', 'Daily:', 'Daily :', 'Daily >', 'Daily>'])) {
            $this->inbox = true;
            $this->save();
        }
    }

    public function add_weekday_task_to_inbox()
    {
        if (Str::startsWith($this->name, ['Weekday', 'weekday'])) {
            $this->inbox = true;
            $this->save();
        }
    }

    public function add_scheduled_task_to_inbox($now = null)
    {
        // @codeCoverageIgnoreStart
        if (! $now) {
            $now = Carbon::now();
        }
        // @codeCoverageIgnoreStart

        $potential_date_str = Str::substr($this->name, 0, 10);
        if (Carbon::canBeCreatedFromFormat($potential_date_str, 'Y-m-d')) {
            $capture_date = Carbon::createFromFormat('Y-m-d', $potential_date_str, 'Asia/Singapore');

            if ($now->isSameDay($capture_date) || $now->greaterThan($capture_date)) {
                $this->inbox = true;
                $this->save();
            }
        }
    }

    //@codeCoverageIgnoreStart
    public function refresh_priority_no()
    {

        $this->priority_no = rand(1, 10000);
        $this->save();
    }
    //@codeCoverageIgnoreEnd

    //@codeCoverageIgnoreStart
    public function change_priority_no($priority_no)
    {

        $this->priority_no = $priority_no;
        $this->save();
    }
    //@codeCoverageIgnoreEnd

    /**
     * Get the user that owns the capture.
     */
    public function user()
    {
        //@codeCoverageIgnoreStart
        return $this->belongsTo(User::class);
        //@codeCoverageIgnoreEnd
    }


    public static function generate_delayed_name_prefix($name, $duration, $now = null)
    {
        //check to see if name already has prefix
        if (! $now) {
            //@codeCoverageIgnoreStart
            $now = now();
            //@codeCoverageIgnoreEnd
        }

        $now->add($duration);
        $dateStr = $now->format('Y-m-d');

        if ($name) {
            $potential_date_str = Str::substr($name, 0, 10);
            if (Carbon::canBeCreatedFromFormat($potential_date_str, 'Y-m-d')) {
                $name = Str::substr($name, 10);
            }

            return $dateStr.' '.trim($name);
        }

        return $dateStr;
    }

    /**
     * Prefix the name with a specific date (Y-m-d), stripping any existing date prefix.
     */
    public static function generate_delayed_name_prefix_for_date($name, $date)
    {
        $dateStr = \Illuminate\Support\Carbon::parse($date)->format('Y-m-d');
        if ($name) {
            $potential_date_str = \Illuminate\Support\Str::substr($name, 0, 10);
            if (\Illuminate\Support\Carbon::canBeCreatedFromFormat($potential_date_str, 'Y-m-d')) {
                $name = \Illuminate\Support\Str::substr($name, 10);
            }
            return $dateStr . ' ' . trim($name);
        }
        return $dateStr;
    }


    //@codeCoverageIgnoreStart
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
        ];
    }
    //@codeCoverageIgnoreEnd
}
