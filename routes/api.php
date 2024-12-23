<?php

use App\Jobs\ProcessTelegramUpdate;
use App\Models\TelegramUpdate;
use App\Models\Capture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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
