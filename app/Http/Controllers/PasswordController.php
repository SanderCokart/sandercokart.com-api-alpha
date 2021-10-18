<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordCompromisedRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
    public function requestPassword(Request $request): string
    {
        $validatedData = $request->validate(['email' => 'required|email']);

        return Password::sendResetLink($validatedData);
    }

    public function passwordReset(Request $request): string
    {
        $validatedData = $request->validate([
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
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

    public function changePassword(Request $request): void
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
            'sign_out_everywhere' => 'required|boolean'
        ]);


        $user = auth()->user();


        if (!Hash::check($validatedData['password'], $user->password)) {
            abort(400, 'The current password didn\'t match the password in our system, please check if you entered the current password correctly');
        }

        $user->password = bcrypt($validatedData['password']);
        $user->save();

        if ($validatedData['sign_out_everywhere'])
            DB::table('sessions')->where('user_id', $user['id'])->where('id', '!=', session()->getId())->delete();


        $token = hash_hmac('sha256', Str::random(40), config('app.key'));;
        DB::table('password_changes')->insert([
            'user_id' => $user->getKey(),
            'token' => $token,
            'created_at' => now(),
            'expire_at' => now()->addYear()
        ]);

        $user->sendPasswordChangeNotification($token);
    }

    public function passwordCompromised(PasswordCompromisedRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user->password = bcrypt($validatedData['password']);

        $user->save();

        DB::table('sessions')->where('user_id', $user['id'])->delete();
    }
}
