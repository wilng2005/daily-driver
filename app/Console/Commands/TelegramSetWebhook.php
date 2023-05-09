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
        //@codeCoverageIgnoreStart
        $url=url('api/telegram/'.env('TELEGRAM_WEBHOOK_URL_TOKEN').'/webhook');
        $response = Telegram::setWebhook(['url' => $url]);

        $journal_bot_url=url('api/telegram_journal/'.env('TELEGRAM_JOURNAL_WEBHOOK_URL_TOKEN').'/webhook');
        $response = Telegram::bot('journalbot')->setWebhook(['url' => $journal_bot_url]);
    
        if($response==true){
            info("Setup of Telegram Webhooks was successful.");
            return Command::SUCCESS;
        }else{
            info("Setup of Telegram Webhooks have failed.");
            return Command::FAILURE;
        }
        //@codeCoverageIgnoreEnd
    }
}
