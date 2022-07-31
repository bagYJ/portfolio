<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @var array
     */
    protected array $conf;

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __destruct()
    {
        Log::channel('request')->info(
            $this->request->url() . ' ' . (Auth::id() ?? null),
            parameterReplace($this->request->all() + Route::current()->parameters())
        );
    }
}
