<?php

namespace App\Nova\Metrics;

use App\Models\TelegramChat;
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

        $cohort_date = \Carbon\Carbon::createFromFormat('M Y', $this->cohort);
        $chats=TelegramChat::where('created_at','>=',$cohort_date->startOfMonth()->toDateString())->where('created_at','<=',$cohort_date->endOfMonth()->toDateString())->get();
        $trend_data=[];
        for($i=0;$i<12;$i++){
            //if cohort date is in the future
            if($chats->count()==0||$cohort_date->startOfMonth()->isFuture()){
                $trend_data[$cohort_date->format('M Y')] = null;
            }else{
                $count=0;

                foreach($chats as $chat){
                    // count the number of TelegramMessages in this chat that was created before $cohort_date's end of month
                    $no_of_messages_sent=$chat->getNoOfMessagesSentOverPeriod(365,$cohort_date->endOfMonth());
                    if($no_of_messages_sent>=10){
                        $count++;
                    }
                }
        
                $trend_data[$cohort_date->format('M Y')] = round($count/$chats->count()*100,2);
            }
            //add one month to cohort date
            $cohort_date->addMonth();
        }
        return (new TrendResult)->trend($trend_data)->showLatestValue();

        // return (new TrendResult)->trend([
        //     "May 2023"=>30,
        //     "Jun 2023"=>40,
        //     "Jul 2023"=>44,
        //     "Aug 2023"=>60,
        //     "Sep 2023"=>null,
        //     "Oct 2023"=>67,
        //     "Nov 2023"=>69,
        //     "Dec 2023"=>70,
        //     "Jan 2024"=>80,
        //     "Feb 2024"=>90,
        //     "Mar 2024"=>100,
        //     "Apr 2024"=>null,
        // ])->showLatestValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
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
