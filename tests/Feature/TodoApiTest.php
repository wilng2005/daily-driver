<?php

namespace Tests\Feature;

use App\Models\Capture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.api_token' => 'test-token']);
    }

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

    public function test_todos_endpoint_requires_token()
    {
        $response = $this->getJson('/api/todos');
        $response->assertStatus(401);
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

        $response = $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'content',
                    'inbox',
                    'next_action',
                    'priority_no'
                ]
            ]);
    }

    public function test_todos_endpoint_excludes_soft_deleted_items()
    {
        // Create an active todo
        Capture::factory()->create([
            'name' => 'Active Todo',
            'content' => 'Active Content',
            'inbox' => true,
            'next_action' => false,
            'priority_no' => 1
        ]);

        // Create a todo that will be soft deleted
        $deletedTodo = Capture::factory()->create([
            'name' => 'Deleted Todo',
            'content' => 'Deleted Content',
            'inbox' => false,
            'next_action' => true,
            'priority_no' => 2
        ]);

        // Soft delete the second todo
        $deletedTodo->delete();

        $response = $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Active Todo'])
            ->assertJsonMissing(['name' => 'Deleted Todo']);
    }
} 