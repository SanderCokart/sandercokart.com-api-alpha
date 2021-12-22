<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ChangeEmailController extends Controller
{
    public function __invoke(Request $request, User $user): void
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email', 'unique:users,email']
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
}
