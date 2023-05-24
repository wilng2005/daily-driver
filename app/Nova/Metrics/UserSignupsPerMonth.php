<?php

namespace App\Nova\Metrics;

use App\Models\TelegramChat;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class UserSignupsPerMonth extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $request->range;

        // produce an array of months for the past x number of months
        $trend_data = [];
        for($i=0;$i<$request->range;$i++){
            $trend_data[now()->subMonths($i)->format('M Y')] = TelegramChat::where('created_at','>=',now()->subMonths($i)->startOfMonth()->toDateString())->where('created_at','<=',now()->subMonths($i)->endOfMonth()->toDateString())->count();
        }
        $trend_data = array_reverse($trend_data);

        return (new TrendResult)->trend($trend_data)->showLatestValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            6 => __('6 Months'),
            12 => __('12 Months'),
            24 => __('24 Months'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        //return now()->addHours(12);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-signups-per-month';
    }
}
