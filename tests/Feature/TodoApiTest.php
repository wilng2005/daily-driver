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

    public function test_next_actions_endpoint_returns_sorted_next_actions()
    {
        // Create captures with various next_action and priority_no values
        $c1 = Capture::factory()->create([
            'name' => 'No Priority',
            'content' => 'No priority, next action',
            'next_action' => true,
            'priority_no' => null,
        ]);
        $c2 = Capture::factory()->create([
            'name' => 'Priority 2',
            'content' => 'Priority 2, next action',
            'next_action' => true,
            'priority_no' => 2,
        ]);
        $c3 = Capture::factory()->create([
            'name' => 'Priority 1',
            'content' => 'Priority 1, next action',
            'next_action' => true,
            'priority_no' => 1,
        ]);
        $c4 = Capture::factory()->create([
            'name' => 'Not Next Action',
            'content' => 'Not next action',
            'next_action' => false,
            'priority_no' => null,
        ]);
        $c5 = Capture::factory()->create([
            'name' => 'Priority 3',
            'content' => 'Priority 3, next action',
            'next_action' => true,
            'priority_no' => 3,
        ]);

        $response = $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/next-actions');

        $response->assertStatus(200)
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

        $returnedNames = array_column($response->json(), 'name');
        // Only next_action=true captures should be returned
        $this->assertNotContains('Not Next Action', $returnedNames);
        // The order should be: No Priority, Priority 1, Priority 2, Priority 3
        $this->assertSame([
            'No Priority',
            'Priority 1',
            'Priority 2',
            'Priority 3',
        ], $returnedNames);
    }

    /**
     * @group api
     */
    public function test_api_can_create_capture()
    {
        // Minimal required payload (only name and content)
        $payload = [
            'name' => 'Test Capture',
            'content' => 'This is a test capture.'
        ];

        $response = $this->withHeaders([
            'X-API-Token' => config('app.api_token'),
            'Accept' => 'application/json',
        ])->postJson('/api/captures', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Test Capture',
                'content' => 'This is a test capture.',
                'inbox' => true,
                'next_action' => true,
            ]);
        $this->assertDatabaseHas('captures', [
            'name' => 'Test Capture',
            'inbox' => true,
            'next_action' => true,
        ]);
        // Assert user_id is always 1 (technical debt workaround)
        $this->assertDatabaseHas('captures', [
            'name' => 'Test Capture',
            'user_id' => 1,
        ]);

        // Explicitly set optional fields
        $payload = [
            'name' => 'Another Capture',
            'content' => 'Optional fields provided',
            'priority_no' => 42,
            'inbox' => false,
            'next_action' => false,
            'ai_delay_suggestion' => 'Delay 3 days',
        ];
        $response = $this->withHeaders([
            'X-API-Token' => config('app.api_token'),
            'Accept' => 'application/json',
        ])->postJson('/api/captures', $payload);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Another Capture',
                'priority_no' => 42,
                'inbox' => false,
                'next_action' => false,
                'ai_delay_suggestion' => 'Delay 3 days',
            ]);
        $this->assertDatabaseHas('captures', [
            'name' => 'Another Capture',
            'priority_no' => 42,
            'inbox' => false,
            'next_action' => false,
        ]);
        // Assert user_id is always 1 (technical debt workaround)
        $this->assertDatabaseHas('captures', [
            'name' => 'Another Capture',
            'user_id' => 1,
        ]);
    }

    /**
     * @group api
     */
    public function test_api_create_capture_requires_validation()
    {
        // Missing required fields
        $payload = [
            // 'name' is missing
            'content' => 'Missing name',
        ];

        $response = $this->withHeaders([
            'X-API-Token' => config('app.api_token'),
            'Accept' => 'application/json',
        ])->postJson('/api/captures', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        // 'content' is missing
        $payload = [
            'name' => 'Missing content',
        ];
        $response = $this->withHeaders([
            'X-API-Token' => config('app.api_token'),
            'Accept' => 'application/json',
        ])->postJson('/api/captures', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);

        // ai_delay_suggestion too long
        $payload = [
            'name' => 'Valid name',
            'content' => 'Valid content',
            'ai_delay_suggestion' => str_repeat('a', 256),
        ];
        $response = $this->withHeaders([
            'X-API-Token' => config('app.api_token'),
            'Accept' => 'application/json',
        ])->postJson('/api/captures', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ai_delay_suggestion']);
    }

    /**
     * @group api
     */
    public function test_api_create_capture_requires_auth()
    {
        $payload = [
            'name' => 'Unauthorized Capture',
            'inbox' => true,
            'next_action' => false,
        ];
        $response = $this->postJson('/api/captures', $payload);
        $response->assertStatus(401);
    }

    public function test_update_capture_endpoint_updates_allowed_fields()
    {
        $capture = \App\Models\Capture::factory()->create([
            'name' => 'Original Name',
            'content' => 'Original content',
            'priority_no' => 1,
            'inbox' => true,
            'next_action' => false,
        ]);

        $payload = [
            'name' => 'Updated Name',
            'content' => 'Updated content',
            'priority_no' => 5,
            'inbox' => false,
            'next_action' => true,
        ];

        $response = $this->withHeader('X-API-Token', 'test-token')
            ->putJson("/api/captures/{$capture->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment($payload)
            ->assertJsonFragment(['id' => $capture->id]);

        $this->assertDatabaseHas('captures', array_merge(['id' => $capture->id], $payload));
    }

    public function test_update_capture_endpoint_requires_token()
    {
        $capture = \App\Models\Capture::factory()->create();
        $payload = ['name' => 'Should Fail', 'content' => '...', 'priority_no' => 1, 'inbox' => false, 'next_action' => false];
        $response = $this->putJson("/api/captures/{$capture->id}", $payload);
        $response->assertStatus(401);
    }

    public function test_update_capture_endpoint_validates_fields()
    {
        $capture = \App\Models\Capture::factory()->create();
        $invalidPayloads = [
            // name is required
            ['name' => '', 'content' => '...', 'priority_no' => 1, 'inbox' => false, 'next_action' => false],
            // name too long
            ['name' => str_repeat('a', 256), 'content' => '...', 'priority_no' => 1, 'inbox' => false, 'next_action' => false],
            // priority_no negative
            ['name' => 'Valid', 'content' => '...', 'priority_no' => -1, 'inbox' => false, 'next_action' => false],
            // inbox not boolean
            ['name' => 'Valid', 'content' => '...', 'priority_no' => 1, 'inbox' => 'notabool', 'next_action' => false],
            // next_action not boolean
            ['name' => 'Valid', 'content' => '...', 'priority_no' => 1, 'inbox' => false, 'next_action' => 'notabool'],
            // ai_delay_suggestion too long
            ['name' => 'Valid', 'content' => '...', 'priority_no' => 1, 'inbox' => false, 'next_action' => false, 'ai_delay_suggestion' => str_repeat('a', 256)],
        ];
        foreach ($invalidPayloads as $payload) {
            $response = $this->withHeader('X-API-Token', 'test-token')
                ->putJson("/api/captures/{$capture->id}", $payload);
            $response->assertStatus(422);
        }
    }

    /**
     * @group api
     */
    public function test_soft_delete_capture(): void
    {
        $capture = Capture::factory()->create();
        $headers = ['X-API-Token' => config('app.api_token')];

        $response = $this->withHeaders($headers)->deleteJson("/api/captures/{$capture->id}");
        $response->assertNoContent();
        $this->assertSoftDeleted('captures', ['id' => $capture->id]);
    }

    /**
     * @group api
     */
    public function test_soft_delete_capture_not_found(): void
    {
        $headers = ['X-API-Token' => config('app.api_token')];
        $response = $this->withHeaders($headers)->deleteJson('/api/captures/999999');
        $response->assertNotFound();
    }

    /**
     * @group api
     */
    public function test_soft_delete_capture_unauthorized(): void
    {
        $capture = Capture::factory()->create();
        $response = $this->deleteJson("/api/captures/{$capture->id}");
        $response->assertStatus(401);
    }

    public function test_update_capture_endpoint_cannot_update_capture_id_or_user_id()
    {
        $capture = \App\Models\Capture::factory()->create([
            'name' => 'Original',
            'user_id' => 123,
        ]);
        $payload = [
            'name' => 'Updated',
            'capture_id' => 999,
            'user_id' => 999,
            'content' => 'Updated',
            'priority_no' => 1,
            'inbox' => false,
            'next_action' => false,
        ];
        $response = $this->withHeader('X-API-Token', 'test-token')
            ->putJson("/api/captures/{$capture->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonMissing(['capture_id' => 999, 'user_id' => 999]);
        $this->assertDatabaseMissing('captures', ['id' => $capture->id, 'capture_id' => 999]);
        $this->assertDatabaseHas('captures', ['id' => $capture->id, 'name' => 'Updated']);
    }
}