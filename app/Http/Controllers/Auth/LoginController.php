<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
            'remember_me' => 'required|boolean',
        ]);


        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            $this->authenticateFrontend();
        } else {
            // use token auth
        }

        $responses = [
            'Hey ' . $request->user()->name . '! Welcome back!',
            'You are logged in!',
            'Welcome back!',
            'We missed you!'
        ];

        return response()->json([
            'message' => $responses[rand(0, sizeof($responses) - 1)],
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    private function authenticateFrontend()
    {
        if (!auth()->guard('web')
            ->attempt(
                request()->only(['email', 'password']),
                request()->boolean('remember_me')
            )) {
            throw ValidationException::withMessages([
                'email' => __('auth.email_match_password'),
                'password' => __('auth.password_match_email'),
            ]);
        }
    }
}
