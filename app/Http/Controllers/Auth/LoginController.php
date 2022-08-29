<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'email'       => 'string|required|email',
            'password'    => 'string|required',
            'remember_me' => 'boolean|required',
        ]);


        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            $this->authenticateFrontend();
        } else {
            dd(false);
            // use token auth
        }

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    private function authenticateFrontend(): void
    {
        if (! auth()
            ->guard('web')
            ->attempt(
                request()->only(['email', 'password']),
                request()->boolean('remember_me'))

        ) {

            throw ValidationException::withMessages([
                'email'    => __('auth.email_match_password'),
                'password' => __('auth.password_match_email'),
            ]);
        }
    }
}
