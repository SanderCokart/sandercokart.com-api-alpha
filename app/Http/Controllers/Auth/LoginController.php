<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'required|boolean',
        ]);


        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            $this->authenticateFrontend();
        } else {
            // use token auth
        }

        if (!auth()->attempt(
            $request->only(['email', 'password']),
            $request->boolean('remember_me')
        )) {
            abort(401, 'Incorrect email or password!');
        }

        return response()->json(['user' => new UserResource($request->user())]);
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
                'email' => __('auth.failed')
            ]);
        }
    }
}
