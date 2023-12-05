<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\IncomingTelegramMessagesPerDay;
use App\Nova\Metrics\PercentageOfCustomersThatAchieveValue;
use App\Nova\Metrics\TelegramChatsPerMonth;
use App\Nova\Metrics\ThingsToDo;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     */
    public function cards(): array
    {
        $cards = [
            new ThingsToDo,
            new TelegramChatsPerMonth,
            new IncomingTelegramMessagesPerDay,
            //(PercentageOfCustomersThatAchieveValue::make(now()->subMonths(4)->format('M Y')))->width('full')
        ];

        for ($i = 0; $i < 12; $i++) {
            $cards[] = (PercentageOfCustomersThatAchieveValue::make(now()->subMonths($i)->format('M Y')))->width('full');
        }

        return $cards;
    }
}
