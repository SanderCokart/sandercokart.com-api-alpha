<?php

namespace App\Traits;

use App\Models\Authenticatable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @mixin User
 */
trait HasTokenSecurity
{
    public function generateUrlWithIdentifierAndToken(string $table, string $type, string $routeName, ?bool $absolute = true, ?string $identifier = null, ?string $token = null, ?User $user = null): string
    {
        $identifier = $identifier ?? $this->generateIdentifier();
        $token = $token ?? $this->generateToken();

        $this->insertTokenAndIdentifierIntoDatabase($table, $identifier, $token, $user);

        return route($routeName, [
            'identifier' => $identifier,
            'token'      => $token,
            'type'       => $type,
        ], $absolute);
    }

    public function generateIdentifier(): string
    {
        return Str::uuid()->toString();
    }

    public function generateToken(): string
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function insertTokenAndIdentifierIntoDatabase(string $table, string $identifier, string $token, ?User $user = null): bool
    {
        if ($user)
            return DB::table($table)->updateOrInsert([
                'user_id' => $user->getKey(),
            ], [
                'user_id'    => $user->getKey(),
                'identifier' => $identifier,
                'token'      => $token,
                'expires_at' => $this->freshTimestamp()->addHour(),
            ]);

        return DB::table($table)
                 ->updateOrInsert([
                     'identifier' => $identifier,
                     'token'      => $token,
                     'expires_at' => $this->freshTimestamp()->addHour(),
                 ]);
    }
}
