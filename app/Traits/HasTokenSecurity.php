<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @mixin User
 */
trait HasTokenSecurity
{
    public function generateUrlWithIdentifierAndToken(string $table, string $type, string $routeName, ?bool $absolute = true, ?string $identifier = null, ?string $token = null): string
    {
        $identifier = $identifier ?? $this->generateIdentifier();
        $token = $token ?? $this->generateToken();

        $this->insertTokenAndIdentifierIntoDatabase($table, $identifier, $token);

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

    public function insertTokenAndIdentifierIntoDatabase(string $table, string $identifier, string $token): void
    {
        DB::table($table)->insert([
            'identifier' => $identifier,
            'token'      => $token,
            'expires_at' => $this->freshTimestamp()->addHour()
        ]);
    }

}
