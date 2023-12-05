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
    public function handle(): int
    {
        //@codeCoverageIgnoreStart
        $url = url('api/telegram/dsYeN7rvWz3sGk88X9X4LbQt/webhook');
        $response = Telegram::setWebhook(['url' => $url]);

        if (env('APP_ENV') != 'production') {
            info('TELEGRAM_WEBHOOK_URL='.$url);
        }

        if ($response == true) {
            info('Setup of Telegram Webhook was successful.');

            return Command::SUCCESS;
        } else {
            info('Setup of Telegram Webhook was a failure.');

            return Command::FAILURE;
        }
        //@codeCoverageIgnoreEnd
    }
}
