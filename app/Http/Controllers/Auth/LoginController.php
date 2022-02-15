<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): \Illuminate\Http\Response
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

        return response()->noContent();
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
