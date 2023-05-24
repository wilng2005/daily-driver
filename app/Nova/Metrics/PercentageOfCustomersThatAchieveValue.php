<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class PercentageOfCustomersThatAchieveValue extends Trend
{
    //constructor
    public function __construct(public $cohort=null){
        $this->name = "% of Customers that Achieve Value";
        
        if(!isset($this->cohort)){
            $this->cohort=now()->format('M Y');
        }
        $this->name = "% of ".$this->cohort." chats that achieve value";
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return (new TrendResult)->trend([
            "May 2023"=>30,
            "Jun 2023"=>40,
            "Jul 2023"=>44,
            "Aug 2023"=>60,
            "Sep 2023"=>66,
            "Oct 2023"=>67,
            "Nov 2023"=>69,
            "Dec 2023"=>70,
            "Jan 2024"=>80,
            "Feb 2024"=>90,
            "Mar 2024"=>100,
            "Apr 2024"=>100,
        ])->showLatestValue();
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
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'percentage-of-customers-that-achieve-value';
    }
}