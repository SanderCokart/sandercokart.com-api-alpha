<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $validatedData = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'string', 'max:50', 'confirmed'],
        ]);

        /** @var User $user */
        $user = $request->user();


        if (!Hash::check($validatedData['current_password'], $user->password)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'The current password didn\'t match the password in our system, please check if you entered the current password correctly');
        }

        $user->changePasswordAndNotify($validatedData['password']);

        return response()->noContent();
    }
}
