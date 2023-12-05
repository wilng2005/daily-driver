<?php

namespace App\Nova\Metrics;

use App\Models\TelegramMessage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class IncomingTelegramMessagesPerDay extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $request->range;

        $trend_data = [];
        for ($i = 0; $i < $request->range; $i++) {
            $temp_date = now()->subDays($i);
            $trend_data[$temp_date->format('d M Y')] = TelegramMessage::incomingDailyMessageCount($temp_date);
        }
        $trend_data = array_reverse($trend_data);

        return (new TrendResult)->trend($trend_data)->showLatestValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges(): array
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
        //return now()->addMinutes(1);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey(): string
    {
        return 'incoming-telegram-messages-per-day';
    }
}
