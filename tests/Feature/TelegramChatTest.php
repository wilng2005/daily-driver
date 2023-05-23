<?php

namespace Tests\Feature;

use App\Models\TelegramChat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TelegramChatTest extends TestCase
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

        $response->assertStatus(302);
    }

    public function test_getJournalEntryPrompt(){
        $telegramChat = TelegramChat::factory()->create();
        $telegramChat->configuration=[
            "AI_ENABLED"=> "TRUE",
            "ACTIVE_JOURNAL"=> "TRUE",
            "NEW_CONTEXT_PROMPT"=> "...",
            "JOURNAL_ENTRY_PROMPT"=> "Prompt 1",
            "SYSTEM_CONTEXT_PROMPT"=> "You are an AI assistant similar to ChatGPT, powered by OpenAI.",
            "NO_OF_HISTORICAL_MESSAGES_TO_USE"=> 10,
        ];

        $this->assertEquals("Prompt 1", $telegramChat->getJournalEntryPrompt());
        
        $telegramChat->configuration=[
            "AI_ENABLED"=> "TRUE",
            "ACTIVE_JOURNAL"=> "TRUE",
            "NEW_CONTEXT_PROMPT"=> "...",
            "SYSTEM_CONTEXT_PROMPT"=> "You are an AI assistant similar to ChatGPT, powered by OpenAI.",
            "NO_OF_HISTORICAL_MESSAGES_TO_USE"=> 10,
        ];

        $this->assertEquals(TelegramChat::DEFAULT_PROMPT, $telegramChat->getJournalEntryPrompt());

        $telegramChat->configuration=[
            "AI_ENABLED"=> "TRUE",
            "ACTIVE_JOURNAL"=> "TRUE",
            "NEW_CONTEXT_PROMPT"=> "...",
            "JOURNAL_ENTRY_PROMPT"=> "Prompt 1|Prompt 2",
            "SYSTEM_CONTEXT_PROMPT"=> "You are an AI assistant similar to ChatGPT, powered by OpenAI.",
            "NO_OF_HISTORICAL_MESSAGES_TO_USE"=> 10,
        ];
        //assert that either the first or second prompt is returned but not both
        $this->assertContains($telegramChat->getJournalEntryPrompt(), ["Prompt 1", "Prompt 2"]);
    }

    function test_hasReceivedMessageFromUserOverPeriod(){
        $telegramChat = TelegramChat::factory()->create();
        $telegramChat->configuration=[
            "AI_ENABLED"=> "TRUE",
            "ACTIVE_JOURNAL"=> "TRUE",
            "NEW_CONTEXT_PROMPT"=> "...",
            "JOURNAL_ENTRY_PROMPT"=> "Prompt 1|Prompt 2",
            "SYSTEM_CONTEXT_PROMPT"=> "You are an AI assistant similar to ChatGPT, powered by OpenAI.",
            "NO_OF_HISTORICAL_MESSAGES_TO_USE"=> 10,
        ];

        $this->assertFalse($telegramChat->hasReceivedMessageFromUserOverPeriod(1));

        $telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);

        $this->assertTrue($telegramChat->hasReceivedMessageFromUserOverPeriod(1));
    }
}