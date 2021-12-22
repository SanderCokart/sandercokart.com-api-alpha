<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $validatedData = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'string', 'max:50', 'confirmed'],
            'sign_out_everywhere' => ['required', 'boolean']
        ]);


        $user = $request->user();


        if (!Hash::check($validatedData['current_password'], $user->password)) {
            abort(400, 'The current password didn\'t match the password in our system, please check if you entered the current password correctly');
        }

        $user->fill(['password' => bcrypt($validatedData['password'])])->save();

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

        return response()->noContent();
    }
}
