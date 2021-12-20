<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegisterController extends Controller
{

    public function __invoke(Request $request): void
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string||email|unique:users',
            'password' => ['string', 'required', 'max:50', PasswordRule::min(8)->symbols()->mixedCase()->numbers()],
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        $user->sendEmailVerificationNotification();
    }
}
