<?php

namespace App\Jobs;

use App\Models\TelegramChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;


class EncourageUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;
 
    /**
     * Create a new job instance.
     *
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function __construct(public TelegramChat $telegramChat)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //@codeCoverageIgnoreStart
        if($this->telegramChat){
            //$this->telegramChat->executeAIResponse();
            
            $this->telegramChat->encourageUser();
            
        }
            

        //@codeCoverageIgnoreEnd
    }


    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        //@codeCoverageIgnoreStart
        return [(new RateLimited('EncourageUser'))->dontRelease()];
        //@codeCoverageIgnoreEnd
    }
}
