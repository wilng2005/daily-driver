<?php

use App\Jobs\ProcessTelegramUpdate;
use App\Models\TelegramUpdate;
use App\Models\Capture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\OpenAiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// OpenAI Random Number Endpoints (public)
Route::get('open-ai/random-number', [OpenAiController::class, 'randomNumber']);
Route::get('open-ai/random-number/schema', [OpenAiController::class, 'randomNumberSchema']);

// OpenAI Timestamp Endpoints (public)
Route::get('open-ai/timestamp', [OpenAiController::class, 'timestamp']);
Route::get('open-ai/timestamp/schema', [OpenAiController::class, 'timestampSchema']);

Route::get('open-ai/schema', function () {



    return response()->json([
        "openapi" => "3.1.0",
        "info" => [
            "title" => "Todo List API",
            "description" => "API for retrieving todo items and tasks",
            "version" => "v1.0.0"
        ],
        "servers" => [
            [
                "url" => config('app.url') // This will use your Laravel app URL
            ]
        ],
        "paths" => [
            "/api/todos" => [
                "get" => [
                    "description" => "Get a list of todo items based on a search query",
                    "operationId" => "searchTodos",
                    "parameters" => [
                        [
                            "name" => "search",
                            "in" => "query",
                            "description" => "Search term to filter todo items by name or content",
                            "required" => false,
                            "schema" => [
                                "type" => "string"
                            ]
                        ]
                    ],
                    "responses" => [
                        "200" => [
                            "description" => "List of todo items",
                            "content" => [
                                "application/json" => [
                                    "schema" => [
                                        "type" => "array",
                                        "items" => [
                                            '$ref' => '#/components/schemas/Todo'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "/api/captures" => [
                "post" => [
                    "description" => "Create a new capture (todo) item.",
                    "operationId" => "createCapture",
                    "requestBody" => [
                        "required" => true,
                        "content" => [
                            "application/json" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "name" => ["type" => "string", "maxLength" => 255, "description" => "Title or label for the capture (required)"],
                                        "content" => ["type" => "string", "nullable" => true, "description" => "Body or description (markdown supported)"],
                                        "priority_no" => ["type" => "integer", "nullable" => true, "minimum" => 0, "description" => "Priority number (nullable)"],
                                        "inbox" => ["type" => "boolean", "description" => "Is in inbox (optional, defaults to true)"],
                                        "next_action" => ["type" => "boolean", "description" => "Is a next action (optional, defaults to true)"]
                                    ],
                                    "required" => ["name", "content"]
                                ]
                            ]
                        ]
                    ],
                    "responses" => [
                        "201" => [
                            "description" => "The created capture",
                            "content" => [
                                "application/json" => [
                                    "schema" => [
                                        '$ref' => '#/components/schemas/Todo'
                                    ]
                                ]
                            ]
                        ],
                        "401" => ["description" => "Unauthorized"],
                        "422" => ["description" => "Validation error"]
                    ],
                    "security" => [["ApiTokenAuth" => []]]
                ]
            ],
            "/api/captures/{id}" => [
                "put" => [
                    "description" => "Update a capture by ID. Only 'name', 'content', 'priority_no', 'inbox', and 'next_action' are updatable.",
                    "operationId" => "updateCapture",
                    "parameters" => [
                        [
                            "name" => "id",
                            "in" => "path",
                            "required" => true,
                            "description" => "ID of the capture to update",
                            "schema" => ["type" => "integer"]
                        ]
                    ],
                    "requestBody" => [
                        "required" => true,
                        "content" => [
                            "application/json" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "name" => ["type" => "string", "maxLength" => 255, "description" => "Title or label for the capture (required)"],
                                        "content" => ["type" => "string", "nullable" => true, "description" => "Body or description (markdown supported)"],
                                        "priority_no" => ["type" => "integer", "nullable" => true, "minimum" => 0, "description" => "Priority number (nullable)"],
                                        "inbox" => ["type" => "boolean", "description" => "Is in inbox"],
                                        "next_action" => ["type" => "boolean", "description" => "Is a next action"]
                                    ],
                                    "required" => ["name", "inbox", "next_action"]
                                ]
                            ]
                        ]
                    ],
                    "responses" => [
                        "200" => [
                            "description" => "The updated capture",
                            "content" => [
                                "application/json" => [
                                    "schema" => [
                                        '$ref' => '#/components/schemas/Todo'
                                    ]
                                ]
                            ]
                        ],
                        "401" => ["description" => "Unauthorized"],
                        "422" => ["description" => "Validation error"]
                    ],
                    "security" => [["ApiTokenAuth" => []]]
                ],
                "delete" => [
                    "description" => "Soft delete a capture by ID. Marks the capture as deleted (using Laravel SoftDeletes) without removing it from the database.",
                    "operationId" => "softDeleteCapture",
                    "parameters" => [
                        [
                            "name" => "id",
                            "in" => "path",
                            "required" => true,
                            "description" => "ID of the capture to soft delete",
                            "schema" => ["type" => "integer"]
                        ]
                    ],
                    "responses" => [
                        "204" => ["description" => "Capture soft deleted successfully (No Content)"] ,
                        "401" => ["description" => "Unauthorized"],
                        "403" => ["description" => "Forbidden (invalid or missing API token)"],
                        "404" => ["description" => "Capture not found"]
                    ],
                    "security" => [["ApiTokenAuth" => []]]
                ]
            ],
            "/api/next-actions" => [
                "get" => [
                    "description" => "Get all captures marked as Next Actions, sorted by priority (no priority first, then ascending)",
                    "operationId" => "getNextActions",
                    "responses" => [
                        "200" => [
                            "description" => "List of next action captures",
                            "content" => [
                                "application/json" => [
                                    "schema" => [
                                        "type" => "array",
                                        "items" => [
                                            '$ref' => '#/components/schemas/Todo'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        "components" => [
            "securitySchemes" => [
                "ApiTokenAuth" => [
                    "type" => "apiKey",
                    "in" => "header",
                    "name" => "X-API-Token"
                ]
            ],
            "schemas" => [
                "Todo" => [
                    "type" => "object",
                    "properties" => [
                        "id" => [
                            "type" => "integer",
                            "description" => "Unique identifier for the todo item"
                        ],
                        "name" => [
                            "type" => "string",
                            "description" => "Title or brief description of the todo item"
                        ],
                        "content" => [
                            "type" => "string",
                            "description" => "Detailed notes or additional information about the todo item. Content is typically markdown formatted."
                        ],
                        "inbox" => [
                            "type" => "boolean",
                            "description" => "Indicates if the item is in the inbox (GTD methodology's collection phase)"
                        ],
                        "next_action" => [
                            "type" => "boolean",
                            "description" => "Indicates if this is the next actionable item (GTD methodology's next actions list)"
                        ],
                        "priority_no" => [
                            "type" => "integer",
                            "description" => "Priority level of the todo item (lower numbers indicate higher priority)"
                        ]
                    ]
                ]
            ]
        ],
        "security" => [
            ["ApiTokenAuth" => []]
        ]
    ]);
});

Route::middleware('api.token')->group(function () {
    Route::get('todos', function (Request $request) {
        $searchTerm = $request->input('search', '');

        // Perform the search using Scout
        $todos = Capture::search($searchTerm)
            ->take(25) // Limit to top 25 matches
            ->get();

        return response()->json($todos);
    });

    Route::put('captures/{id}', [\App\Http\Controllers\CaptureController::class, 'update']);
    Route::post('captures', [\App\Http\Controllers\CaptureController::class, 'store']);
    Route::delete('captures/{id}', [\App\Http\Controllers\CaptureController::class, 'destroy']);

    Route::get('next-actions', function () {
        // Return all captures where next_action=true, sorted: priority_no=null first, then ascending
        $captures = Capture::where('next_action', true)
            ->orderByRaw('priority_no IS NOT NULL') // nulls first
            ->orderBy('priority_no', 'asc')
            ->get();
        return response()->json($captures);
    });
});

Route::post('telegram/dsYeN7rvWz3sGk88X9X4LbQt/webhook', function () {
    //$updates = Telegram::getWebhookUpdate();
    $updates = Telegram::commandsHandler(true);

    info('telegram webhook received:');
    info($updates);

    $telegram_update = TelegramUpdate::create([
        'data' => $updates,
    ]);

    //check if the message is a command
    $is_bot_command = false;

    if (isset($telegram_update->data['message']['entities'])) {
        foreach ($telegram_update->data['message']['entities'] as $entity) {
            if ($entity['type'] == 'bot_command') {
                $is_bot_command = true;
                break;
            }
        }
    }

    if (! $is_bot_command) {
        ProcessTelegramUpdate::dispatch($telegram_update);
    }

    return 'ok';
});
