<?php

use App\Jobs\ProcessTelegramUpdate;
use App\Models\TelegramUpdate;
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
          "title" => "Get weather data",
          "description" => "Retrieves current weather data for a location.",
          "version" => "v1.0.0"
        ],
        "servers" => [
          [
            "url" => "https://weather2.example.com"
          ]
        ],
        "paths" => [
          "/location" => [
            "get" => [
              "description" => "Get temperature for a specific location",
              "operationId" => "GetCurrentWeather",
              "parameters" => [
                [
                  "name" => "location",
                  "in" => "query",
                  "description" => "The city and state to retrieve the weather for",
                  "required" => true,
                  "schema" => [
                    "type" => "string"
                  ]
                ]
              ],
              "deprecated" => false
            ]
          ]
        ],
        "components" => [
          "schemas" => (object) []
        ]
    ]);
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
