<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramSetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setwebhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers the webhook url of this application with telegram.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $url=url(env('TELEGRAM_WEBHOOK_URL_TOKEN').'/webhook');
        $response = Telegram::setWebhook(['url' => $url]);
        
        if(env('APP_ENV')!='production')
            info("TELEGRAM_WEBHOOK_URL=".$url);

        if($response['ok']==true){
            return Command::SUCCESS;
        }else{
            return Command::FAILURE;
        }
    }
}
