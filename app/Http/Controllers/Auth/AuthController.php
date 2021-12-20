<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function auth;
use function response;

class AuthController extends Controller
{
    public function user(Request $request): JsonResponse|Response
    {
        if ($user = $request->user())
            return response()->json(['user' => new UserResource($user)]);
        else
            return response()->noContent();

    }

    public function logout(): void
    {
        auth()->guard('web')->logout();
    }
}
