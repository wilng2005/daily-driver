<?php

namespace Tests\Feature;

use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelegramMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_incoming_daily_message_count($date = null)
    {
        // create a case where there are 3 messages created today
        $telegramChat = TelegramChat::factory()->create();
        $telegramChat->configuration = [
            'AI_ENABLED' => 'TRUE',
            'ACTIVE_JOURNAL' => 'TRUE',
            'NEW_CONTEXT_PROMPT' => '...',
            'JOURNAL_ENTRY_PROMPT' => 'Prompt 1|Prompt 2',
            'SYSTEM_CONTEXT_PROMPT' => 'You are an AI assistant similar to ChatGPT, powered by OpenAI.',
            'NO_OF_HISTORICAL_MESSAGES_TO_USE' => 10,
        ];

        //create three messages
        $telegramChat->telegramMessages()->create([
            'data' => [],
            'telegram_chat_id' => $telegramChat->id,
            'text' => 'Test1',
            'is_incoming' => true,
            'is_outgoing' => false,
            'from_username' => TelegramChat::USER_ROLE,
        ]);

        $telegramChat->telegramMessages()->create([
            'data' => [],
            'telegram_chat_id' => $telegramChat->id,
            'text' => 'Test2',
            'is_incoming' => true,
            'is_outgoing' => false,
            'from_username' => TelegramChat::USER_ROLE,
        ]);

        $telegramChat->telegramMessages()->create([
            'data' => [],
            'telegram_chat_id' => $telegramChat->id,
            'text' => 'Test3',
            'is_incoming' => true,
            'is_outgoing' => false,
            'from_username' => TelegramChat::USER_ROLE,
        ]);

        // assert that indeed count is three

        $this->assertEquals(3, TelegramMessage::incomingDailyMessageCount());
    }
}
