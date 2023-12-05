<?php

namespace App\Jobs;

use App\Models\TelegramChat;
use Illuminate\Bus\Queueable;
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
    public function handle(): void
    {
        //@codeCoverageIgnoreStart
        if ($this->telegramChat) {

            //check to see if the user had any activity in the last 10 minutes
            if ($this->telegramChat->getLastActivity()->diffInMinutes(now()) < 10) {
                EncourageUserJob::dispatch($this->telegramChat)->delay(now()->addMinutes(15));
            } else {
                $this->telegramChat->encourageUser();
            }
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
