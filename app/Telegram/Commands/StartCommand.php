<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start Command to get you started';

    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'This is an AI-powered journaling bot that is currently available for trial usage.

            To participate in the trial, please review the trial briefing slides at  
            https://drive.google.com/file/d/1PiEpiL-TO39kDX51P_-7ify7jMj--Rwt/view?usp=share_link
            
            Then, fill up the onboarding form https://forms.gle/LabqthSPNLVTfiv8A
             
            Once I’ve gotten your details, I’ll configure your trial usage to begin. Please contact me on telegram @wilng2005 if you encounter any issues, or have any further clarifications.',
        ]);
    }
}