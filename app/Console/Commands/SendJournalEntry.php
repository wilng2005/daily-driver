<?php

namespace App\Console\Commands;

use App\Models\TelegramChat;
use Illuminate\Console\Command;

class SendJournalEntry extends Command
{
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
        //@codeCoverageIgnoreStart
        foreach (TelegramChat::all() as $telegramChat) {
            if ($telegramChat->hasReceivedMessageFromUserOverPeriod(2) && $telegramChat->isActiveJournal()) {
                $telegramChat->sendJournalEntry();
            }
        }

        return Command::SUCCESS;
        //@codeCoverageIgnoreEnd
    }
}
