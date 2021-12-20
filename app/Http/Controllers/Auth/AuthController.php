<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class AuthController extends Controller
{
    public function user(Request $request): UserResource|Response
    {
        if ($user = $request->user())
            return new UserResource($user);
        else
            return response()->noContent();

    }
}
