<?php

namespace Tests\Feature;

use App\Models\Capture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_openai_schema_endpoint_returns_correct_structure()
    {
        $response = $this->getJson('/api/open-ai/schema');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'openapi',
                'info' => ['title', 'description', 'version'],
                'servers' => [['url']],
                'paths' => [
                    '/api/todos' => [
                        'get' => [
                            'description',
                            'operationId',
                            'responses' => [
                                '200' => [
                                    'description',
                                    'content' => [
                                        'application/json' => [
                                            'schema' => [
                                                'type',
                                                'items'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'components' => [
                    'schemas' => [
                        'Todo' => [
                            'type',
                            'properties' => [
                                'id' => ['type', 'description'],
                                'name' => ['type', 'description'],
                                'content' => ['type', 'description'],
                                'inbox' => ['type', 'description'],
                                'next_action' => ['type', 'description'],
                                'priority_no' => ['type', 'description']
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_todos_endpoint_returns_todos()
    {
        // Create some test todos
        Capture::factory()->create([
            'name' => 'Test Todo 1',
            'content' => 'Content 1',
            'inbox' => true,
            'next_action' => false,
            'priority_no' => 1
        ]);

        Capture::factory()->create([
            'name' => 'Test Todo 2',
            'content' => 'Content 2',
            'inbox' => false,
            'next_action' => true,
            'priority_no' => 2
        ]);

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name'
                ]
            ]);
    }
} 