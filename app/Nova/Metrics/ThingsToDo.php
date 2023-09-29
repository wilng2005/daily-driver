<?php

namespace App\Nova\Metrics;

use App\Models\Capture;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class ThingsToDo extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $dailySnapshots = \App\Models\DailySnapshot::where('date','>=',now()->subDays($request->range)->toDateString())->get();
        $trend = [];
        foreach($dailySnapshots as $dailySnapshot){
            $trend[$dailySnapshot->date->format("F j")] = 
                isset($dailySnapshot->data['productivity']['start_of_day']['no_of_inbox_next_action_captures'])?
                $dailySnapshot->data['productivity']['start_of_day']['no_of_inbox_next_action_captures']:0;
        }

        return (new TrendResult)->trend($trend)->showLatestValue();
    } 

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            90 => __('90 Days'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        return now()->addHours(4);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'things-to-do';
    }
}
