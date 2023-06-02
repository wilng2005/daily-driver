<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start Command to get you started';

    public function handle()
    {
        //if this is staging environment, reply with staging message
        if (app()->environment(['production'])) {
            

            $this->replyWithMessage([
            'text' => '
This is an AI-powered journaling bot that is currently available for trial usage.

To participate in the trial, please fill up the onboarding form https://forms.gle/LabqthSPNLVTfiv8A

Once I’ve gotten your details, I’ll configure your trial usage to begin.

To find out more about the trial, please check out the trial briefing slides at  
https://drive.google.com/file/d/1PiEpiL-TO39kDX51P_-7ify7jMj--Rwt/view?usp=share_link
             
Please contact me on telegram @wilng2005 if you encounter any issues, or have any further clarifications.',
            ]);
        } else {
            $this->replyWithMessage([
                'text' => '
This bot is not for public usage. 

If you are looking for an AI-powered journaling bot, please use t.me/GreaterThanTodayBot
Have a nice day!'
            ]);
        }
    }
}