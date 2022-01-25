<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use function response;

class AuthController extends Controller
{
    public function user(Request $request): UserResource|Response
    {
        return new UserResource(User::with('roles')->find(Auth::id()));
    }
}
