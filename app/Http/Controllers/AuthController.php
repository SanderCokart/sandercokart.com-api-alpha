<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        event(new Registered($user));

        return response()->json($user);
    }

    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'Email has been verified!',
        ], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'required|boolean',
        ]);

        $remember_me = $validatedData['remember_me'] ?? false;
        unset($validatedData['remember_me']);

        if (!auth()->attempt($validatedData, $remember_me)) {
            abort(401, 'Incorrect email or password!');
        }

        return response()->json(['user' => auth()->user()]);
    }

    public function check(): JsonResponse
    {
        return response()->json(['user' => auth()->user()]);
    }

    public function logout(): void
    {
        auth()->guard('web')->logout();
    }

    public function password_forgot(Request $request): JsonResponse
    {
        $validatedData = $request->validate(['email' => 'required|email']);

        return response()->json(['status' => Password::sendResetLink($validatedData)]);
    }

    public function password_reset(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'confirmed|min:6|required',
            'email' => 'email|required',
            'token' => 'required',
        ]);

        Password::reset(
            $validatedData,
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ]);
                /*->setRememberToken(Str::random(60))*/

                $user->save();

                event(new PasswordReset($user));
            }
        );

    }
}
