<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Queues\Socket\ArkServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessArkServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ArkServer $arkServer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ArkServer $arkServer)
    {
        $this->arkServer = $arkServer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->arkServer->init();
    }
}
