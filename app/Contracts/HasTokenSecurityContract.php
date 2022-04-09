<?php

namespace App\Contracts;

interface HasTokenSecurityContract
{
    public function generateIdentifier(): string;

    public function generateToken(): string;

    public function generateUrlWithIdentifierAndToken(string $table, string $type, string $url, ?bool $relative = null, ?string $identifier = null, ?string $token = null): string;

    public function insertTokenAndIdentifierIntoDatabase(string $table, string $identifier, string $token,): void;
}
