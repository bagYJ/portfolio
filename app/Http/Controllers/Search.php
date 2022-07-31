<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\JsonResponse;

class Search extends Controller
{
    public function tag(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'tag' => SearchService::getTags()
        ]);
    }
}
