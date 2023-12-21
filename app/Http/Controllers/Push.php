<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Requests\Push\SendUser;
use Illuminate\Http\JsonResponse;

class Push
{
    public function sendManager(SendUser $request): JsonResponse
    {

        return response()->json([]);
    }

    public function sendUser(): JsonResponse
    {

        return response()->json([]);
    }
}
