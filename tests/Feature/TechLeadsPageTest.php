<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechLeadsPageTest extends TestCase
{
    /**
     * Test that the tech leads page displays a random allowed message each load.
     */
    public function test_random_career_message_is_displayed()
    {
        $messages = [
            "I feel like I’m constantly putting out fires, but not really growing.",
            "I’m stuck. I know something needs to change, but I don’t know what.",
            "I’ve hit a ceiling in my business and can’t see how to move forward.",
            "I’m burning out, but I don’t want to slow down and lose momentum.",
            "I don’t have anyone I can really talk to about this stuff.",
            "Things look fine on the outside, but inside I’m stressed all the time.",
            "I want to lead better, but I keep repeating the same patterns.",
            "I’m scared I’ll mess this up if I keep going like this."
        ];

        $seen = [];
        for ($i = 0; $i < 50; $i++) {
            $response = $this->get('/tech-leads');
            $found = false;
            foreach ($messages as $msg) {
                if (str_contains($response->getContent(), $msg)) {
                    $seen[$msg] = true;
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, 'Response did not contain any allowed message.');
        }
        // Optionally, assert all messages were seen at least once
        $this->assertCount(count($messages), $seen, 'Not all messages appeared after multiple loads.');
    }
}
