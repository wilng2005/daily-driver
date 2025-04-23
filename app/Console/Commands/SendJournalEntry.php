<?php

namespace App\Console\Commands;

use App\Services\TelegramChatProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendJournalEntry extends Command
{
    protected TelegramChatProvider $provider;

    public function __construct(TelegramChatProvider $provider)
    {
        parent::__construct();
        $this->provider = $provider;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'journal_entry:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers the sending of journal entries to the user.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Log::info('[journal_entry:send] Job started');
        try {
            foreach ($this->provider->all() as $telegramChat) {
                if ($telegramChat->hasReceivedMessageFromUserOverPeriod(2) && $telegramChat->isActiveJournal()) {
                    $telegramChat->sendJournalEntry();
                }
            }
            Log::info('[journal_entry:send] Job completed successfully');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('[journal_entry:send] Job failed: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }
}
