<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailChange;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Notification;

class EmailController extends Controller
{
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'Email has been verified!',
        ], 200);
    }

    public function changeEmail(Request $request, User $user): void
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        $oldEmail = $user->email;

        $user->fill(['email' => $validatedData['email'], 'email_verified_at' => null])->save();
        $user->sendEmailVerificationNotification();

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        DB::table('email_changes')->insert([
            'user_id' => $user->getKey(),
            'token' => $token,
            'created_at' => now(),
            'expire_at' => now()->addYear()
        ]);

        Notification::route('mail', $oldEmail)->notify(new EmailChange($token, $user->getKey()));
    }

    /**
     * @param Request $request
     * @param User $user
     */
    public function emailCompromised(Request $request, User $user): void
    {
        $validatedData = $request->validate([
            'email' => 'string|email|unique:users,email',
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
        ]);

        abort_if(!DB::table('email_changes')
            ->where('user_id', (int)$request->route('user')->getKey())
            ->where('token', (string)$request->route('token'))->delete(), '401', 'This user and token combination was not found in our systems.');

        $oldUser = $user->replicate();

        $user->fill(['email' => $validatedData['email'],'password' => $validatedData['password']])->save();

        DB::table('sessions')->where('user_id', $user['id'])->delete();
    }
}
