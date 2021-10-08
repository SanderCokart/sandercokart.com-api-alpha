<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailChangeRequest;
use App\Models\User;
use App\Notifications\EmailChangeNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): void
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => [Password::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50'],
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        event(new Registered($user));
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

    public function request_password(Request $request): string
    {
        $validatedData = $request->validate(['email' => 'required|email']);

        return Password::sendResetLink($validatedData);
    }

    public function request_email(Request $request): string
    {
        $token = hash_hmac('sha256', Str::random(40), env('APP_KEY'));;

        DB::table('email_change_requests')->insert([
            'email' => $request->user()->email,
            'user_id' => $request->user()->getKey(),
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $request->user()->sendEmailChangeNotification($token);
    }

    public function password_reset(Request $request): string
    {
        $validatedData = $request->validate([
            'password' => 'confirmed|min:6|required',
            'email' => 'email|required',
            'token' => 'required',
        ]);

        return Password::reset(
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

    public function email_change(EmailChangeRequest $request)
    {
        $request->fulfill();
    }
}
