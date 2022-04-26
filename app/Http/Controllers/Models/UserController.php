<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UserController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): UserCollection
    {
        $this->authorize('viewAny', User::class);
        $validatedData = $request->validate([
            'page'          => 'integer',
            'perPage'       => 'integer|min:1|max:100',
            'sortBy'        => 'string|in:id,title,created_at,updated_at',
            'sortDirection' => 'string|in:asc,desc',
        ]);

        /* Pagination parameters */
        $perPage = $validatedData['perPage'] ?? 100;
        $sortBy = $validatedData['sortBy'] ?? 'id';
        $sortDirection = $validatedData['sortDirection'] ?? 'asc';

        return new UserCollection(
            User::orderBy($sortBy, $sortDirection)
                ->paginate($perPage)
        );
    }


    /**
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255|min:2',
            'email'    => 'required|string|email|unique:users',
            'password' => ['required', 'string', 'max:50', PasswordRule::defaults()],
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user = User::create($validatedData);
        $user->roles()->attach($validatedData['roles']);

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'User created successfully.'], JsonResponse::HTTP_CREATED);
    }


    /**
     * @throws AuthorizationException
     */
    public function show($userId): UserResource
    {
        $user = User::with('roles')->find($userId);
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $user->delete();

        if (auth()->user()->id === $user->id)
            return response()->json(['message' => 'Account deleted.'], Response::HTTP_OK);

        return response()->json(['message' => 'User deleted.'], Response::HTTP_OK);
    }
}
