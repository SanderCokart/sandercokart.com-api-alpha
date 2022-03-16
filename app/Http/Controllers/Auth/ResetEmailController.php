<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetEmailController extends Controller
{
    /**
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function __invoke(Request $request, User $user): void
    {
        $validatedData = $request->validate([
            'email' => 'string|email|unique:users,email',
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
        ]);

        abort_if(!DB::table('email_changes')
            ->where('user_id', (int)$request->route('user')->getKey())
            ->where('token', (string)$request->route('token'))->delete(), '401', 'This user and token combination was not found in our systems.');

        $oldUser = $user->replicate();

        $user->fill(['email' => $validatedData['email'], 'password' => bcrypt($validatedData['password'])])->save();

        DB::table('sessions')->where('user_id', $user['id'])->delete();
    }
}
