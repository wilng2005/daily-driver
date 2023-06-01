<?php

use App\Models\TelegramUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Jobs\ProcessTelegramUpdate;


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

Route::post('telegram/dsYeN7rvWz3sGk88X9X4LbQt/webhook', function () {
    //$updates = Telegram::getWebhookUpdate();
    $updates = Telegram::commandsHandler(true);

    info("telegram webhook received:");
    info($updates);
    
    $telegram_update=TelegramUpdate::create([
        'data'=> $updates
    ]);

    //check if the message is a command
    $is_bot_command=false;

    if(isset($telegram_update->data['message']['entities'])){
        foreach($telegram_update->data['message']['entities'] as $entity){
            if($entity['type']=='bot_command'){
                $is_bot_command=true;
                break;
            }
        }
    }
    
    if(!$is_bot_command)
        ProcessTelegramUpdate::dispatch($telegram_update);

    return 'ok';
});
