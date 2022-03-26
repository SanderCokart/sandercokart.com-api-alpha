<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $responses = [
            'See you later alligator!',
            'Was nice seeing you!',
            'See you later!',
            'Goodbye!',
            'See you later ' . $request->user()->name . '!'
        ];

        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            auth()->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json([
                'message' => $responses[rand(0, sizeof($responses) - 1)],
            ], JsonResponse::HTTP_OK);
        } else {
            // revoke token
        }
    }
}

