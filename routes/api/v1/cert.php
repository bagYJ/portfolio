<?php

declare(strict_types=1);

use App\Http\Controllers\Cert;
use Illuminate\Support\Facades\Route;

Route::controller(Cert::class)->prefix('cert')->group(function () {
    Route::post('request', 'request');
    Route::post('retry', 'retry');
    Route::post('complete', 'complete');
});
