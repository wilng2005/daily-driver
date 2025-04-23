<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\TelegramChat;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;

class SendJournalEntryTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function test_journal_entry_command_logs_success_and_failure()
    {
        // Success scenario
        Log::spy();
        $mockChat = \Mockery::mock(\App\Models\TelegramChat::class)->makePartial();
        $mockChat->shouldReceive('sendJournalEntry')->once();
        $mockChat->shouldReceive('hasReceivedMessageFromUserOverPeriod')->andReturn(true);
        $mockChat->shouldReceive('isActiveJournal')->andReturn(true);

        $mockProvider = \Mockery::mock(\App\Services\TelegramChatProvider::class);
        $mockProvider->shouldReceive('all')->andReturn(collect([$mockChat]));

        $command = new \App\Console\Commands\SendJournalEntry($mockProvider);
        $command->handle();

        Log::shouldHaveReceived('info')->with('[journal_entry:send] Job started');
        Log::shouldHaveReceived('info')->with('[journal_entry:send] Job completed successfully');

        // Failure scenario
        Log::spy(); // Reset the log spy
        $mockChat = \Mockery::mock(\App\Models\TelegramChat::class)->makePartial();
        $mockChat->shouldReceive('sendJournalEntry')->andThrow(new \Exception('fail!'));
        $mockChat->shouldReceive('hasReceivedMessageFromUserOverPeriod')->andReturn(true);
        $mockChat->shouldReceive('isActiveJournal')->andReturn(true);

        $mockProvider = \Mockery::mock(\App\Services\TelegramChatProvider::class);
        $mockProvider->shouldReceive('all')->andReturn(collect([$mockChat]));

        $command = new \App\Console\Commands\SendJournalEntry($mockProvider);
        try {
            $command->handle();
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            // Exception expected
        }

        Log::shouldHaveReceived('info')->with('[journal_entry:send] Job started');
        Log::shouldHaveReceived('error')->with(\Mockery::on(function ($message) {
            return str_contains($message, '[journal_entry:send] Job failed:');
        }), \Mockery::type('array'));
    }
}
