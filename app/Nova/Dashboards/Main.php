<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\PercentageOfCustomersThatAchieveValue;
use App\Nova\Metrics\ThingsToDo;
use App\Nova\Metrics\TelegramChatsPerMonth;

use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        $cards=[
            new ThingsToDo,
            new TelegramChatsPerMonth,
            //(PercentageOfCustomersThatAchieveValue::make(now()->subMonths(4)->format('M Y')))->width('full')
        ];

        for($i=0;$i<12;$i++){
           $cards[]=(PercentageOfCustomersThatAchieveValue::make(now()->subMonths($i)->format('M Y')))->width('full');
        }

        return $cards;
    }
}
