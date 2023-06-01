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
    ProcessTelegramUpdate::dispatch($telegram_update);
    return 'ok';
});


if(App::isLocal()){
    //don't forget that the route for this is api/telegram/local not telegram/local!
    Route::get('telegram/local',function(){
        $updates = Telegram::getUpdates();
        
        info("telegram getUpdates received:");
        info($updates);
        
        foreach($updates as $update){
            $telegram_update=TelegramUpdate::create([
                'data'=> $update
            ]);
            ProcessTelegramUpdate::dispatch($telegram_update);
        }

        return 'ok';
    });
}



