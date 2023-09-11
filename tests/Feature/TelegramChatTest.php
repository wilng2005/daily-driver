<?php

namespace Tests\Feature;

use App\Models\TelegramChat;
use Carbon\Carbon;
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

        $response->assertStatus(200);
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
    
        $this->assertFalse($telegramChat->hasReceivedMessageFromUserOverPeriod(1,now()->addDays(3)));

    }

    function test_wasLastMessageOutgoing(){
        $telegramChat = TelegramChat::factory()->create();
        $telegramChat->configuration=[
            "AI_ENABLED"=> "TRUE",
            "ACTIVE_JOURNAL"=> "TRUE",
            "NEW_CONTEXT_PROMPT"=> "...",
            "JOURNAL_ENTRY_PROMPT"=> "Prompt 1|Prompt 2",
            "SYSTEM_CONTEXT_PROMPT"=> "You are an AI assistant similar to ChatGPT, powered by OpenAI.",
            "NO_OF_HISTORICAL_MESSAGES_TO_USE"=> 10,
        ];

        $this->assertFalse($telegramChat->wasLastMessageOutgoing());

        $telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test',
            'is_incoming'=>false,
            'is_outgoing'=>true,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);

        $this->assertTrue($telegramChat->wasLastMessageOutgoing());
        
        sleep(1);
        
        $telegramChat->telegramMessages()->create([
            'data'=>['123'=>'456'],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test2',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);

        $this->assertFalse($telegramChat->wasLastMessageOutgoing());
    }

    function test_getNoOfMessagesSentOverPeriod(){
        //test that the method returns the correct number of messages sent over a period

        $telegramChat = TelegramChat::factory()->create();
        
        //test that the method returns 0 when no messages have been sent
        $this->assertEquals(0, $telegramChat->getNoOfMessagesSentOverPeriod(1));

        //test that the method returns 1 when 1 message has been sent
        $message=$telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);
        //change the created_at date to 1 day ago
        $message->created_at=Carbon::create(2021, 1, 23, 0, 0, 0);
        $message->save();

        $message=$telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);

        $message->created_at=Carbon::create(2021, 2, 23, 0, 0, 0);
        $message->save();

        $message=$telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);
        $message->created_at=Carbon::create(2021, 3, 23, 0, 0, 0);
        $message->save();

        $this->assertEquals(0, $telegramChat->getNoOfMessagesSentOverPeriod(365, Carbon::create(2020, 12, 22, 0, 0, 0)));
        $this->assertEquals(0, $telegramChat->getNoOfMessagesSentOverPeriod(365, Carbon::create(2021, 01, 22, 0, 0, 0)));
        $this->assertEquals(1, $telegramChat->getNoOfMessagesSentOverPeriod(365, Carbon::create(2021, 01, 25, 0, 0, 0)));
        $this->assertEquals(2, $telegramChat->getNoOfMessagesSentOverPeriod(365, Carbon::create(2021, 02, 25, 0, 0, 0)));
        $this->assertEquals(3, $telegramChat->getNoOfMessagesSentOverPeriod(365, Carbon::create(2021, 03, 25, 0, 0, 0)));
        $this->assertEquals(0, $telegramChat->getNoOfMessagesSentOverPeriod(365, Carbon::create(2023, 03, 25, 0, 0, 0)));
    }


    function test_performReacquistion(){
        //case 0: the chat has been active, last message is one day before now
        //verify that reacquistion is not performed
        $telegramChat = TelegramChat::factory()->create();
        //change the created_at date to 1 day ago
        $telegramChat->created_at=Carbon::create(2021, 1, 21, 0, 0, 0);
        $telegramChat->save();

        $message=$telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);

        //change the created_at date to 1 day ago
        $message->created_at=Carbon::create(2021, 1, 22, 0, 0, 0);
        $message->save();

        $message=$telegramChat->telegramMessages()->create([
            'data'=>[],
            'telegram_chat_id'=>$telegramChat->id,
            'text'=>'Test2',
            'is_incoming'=>true,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::USER_ROLE,
        ]);

        //change the created_at date to 1 day ago
        $message->created_at=Carbon::create(2021, 1, 23, 0, 0, 0);
        $message->save();

        $result=$telegramChat->performReacquistion(Carbon::create(2021, 1, 24, 0, 0, 0),true);
        $this->assertFalse($result);

        //case 1: the chat has not been active for 3 days
        //verify that reacquistion is performed

        $result=$telegramChat->performReacquistion(Carbon::create(2021, 1, 24, 23, 0, 0),true);
        $this->assertFalse($result);

        $result=$telegramChat->performReacquistion(Carbon::create(2021, 1, 25, 0, 0, 0),true);
        $this->assertFalse($result);

        $result=$telegramChat->performReacquistion(Carbon::create(2021, 1, 29, 0, 0, 0),true);
        $this->assertTrue($result);

        
        // refresh the $telegramChat object with the latest values from db
        $telegramChat->refresh();

        //check that the backoff period has been set to 10 days
        $this->assertEquals(10, $telegramChat->configuration['BACKOFF_PERIOD_IN_DAYS']);

        $result=$telegramChat->performReacquistion(Carbon::create(2021, 1, 24, 23, 0, 0),true);
        $this->assertFalse($result);

        $result=$telegramChat->performReacquistion(Carbon::create(2021, 2, 4, 0, 0, 0),true);
        $this->assertTrue($result);


        // refresh the $telegramChat object with the latest values from db
        $telegramChat->refresh();

        //check that the backoff period has been set to 20 days
        $this->assertEquals(20, $telegramChat->configuration['BACKOFF_PERIOD_IN_DAYS']);

        //user sends a message back to the bot, verify that the backoff is reset
        $telegramChat->resetBackoffPeriod();
        
        $telegramChat->refresh();

        //check that the backoff period has been set to 4 days
        $this->assertFalse(isset($telegramChat->configuration['BACKOFF_PERIOD_IN_DAYS']));

    }

    function test_isActiveJournal(){
        //test if configuration is null
        $telegramChat = TelegramChat::factory()->create();
        $this->assertFalse($telegramChat->isActiveJournal());

        //test if configuration is array but ACTIVE_JOURNAL is not set
        $telegramChat = TelegramChat::factory()->create([
            'configuration'=>[]
        ]);
        $this->assertFalse($telegramChat->isActiveJournal());
        //test if configuration is array and ACTIVE_JOURNAL is set to TRUE
        $telegramChat = TelegramChat::factory()->create([
            'configuration'=>[
                'ACTIVE_JOURNAL'=>true
            ]
        ]);
        $this->assertTrue($telegramChat->isActiveJournal());
        //test if configuration is array and ACTIVE_JOURNAL is set to FALSE
        $telegramChat = TelegramChat::factory()->create([
            'configuration'=>[
                'ACTIVE_JOURNAL'=>false
            ]
        ]);
        $this->assertFalse($telegramChat->isActiveJournal());

        //test if configuration is array and ACTIVE_JOURNAL is set to TRUE
        $telegramChat = TelegramChat::factory()->create([
            'configuration'=>[
                'ACTIVE_JOURNAL'=>'TRUE'
            ]
        ]);
        $this->assertTrue($telegramChat->isActiveJournal());

        //test if configuration is array and ACTIVE_JOURNAL is set to TRUE
        $telegramChat = TelegramChat::factory()->create([
            'configuration'=>[
                'ACTIVE_JOURNAL'=>'FALSE'
            ]
        ]);
        $this->assertFalse($telegramChat->isActiveJournal());

    }
}