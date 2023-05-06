<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;
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

Route::post('telegram/'.env('TELEGRAM_WEBHOOK_URL_TOKEN').'/webhook', function () {
    $updates = Telegram::getWebhookUpdate();
    
    info("telegram webhook received:");
    info($updates);
    
    $result = OpenAI::completions()->create([
        'model' => 'text-davinci-003',
        'prompt' => 'Me:'.$updates->message->text." \nChatGPT:",
        'max_tokens' => 1024
    ]);

    //info($result);
    
    $response = Telegram::sendMessage([
        'chat_id' => $updates->message->chat->id,
        'text' => $result['choices'][0]['text'],
    ]);

    info("hello world sent");
    info('message_id:'.$response->getMessageId());
    return 'ok';
});
