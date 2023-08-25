<?php

namespace App\Nova\Metrics;

use App\Models\TelegramMessage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class IncomingTelegramChatsPerDay extends Trend
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

        $trend_data = [];
        for($i=0;$i<$request->range;$i++){
            $trend_data[now()->subDays($i)->format('d M Y')] = TelegramMessage::where('created_at','>=',now()->subDays($i)->startOfDay()->toDateString())->where('created_at','<=',now()->subDays($i)->endOfDay()->toDateString())->where('is_incoming',true)->count();
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
            7 => __('7 Days'),
            30 => __('30 days'),
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
        return now()->addHours(12);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'incoming-telegram-messages-per-day';
    }
}
