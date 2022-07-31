<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Queues\Fcm\Fcm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFcm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Fcm $fcm;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Fcm $fcm)
    {
        $this->fcm = $fcm;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fcm->init();
    }
}
