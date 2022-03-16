<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;

class AuthController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(new UserResource(User::with('roles')->find(Auth::id())), 200);
    }
}
