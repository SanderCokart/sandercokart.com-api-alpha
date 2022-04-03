<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @mixin User
 */
trait MustVerifyEmail
{
    public function sendEmailVerificationNotification(?string $token = null): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    public function generateVerificationUrl(?string $identifier = null, ?string $token = null, bool $relative = false): string
    {
        $identifier = $identifier ?? $this->generateEmailVerificationIdentifier();
        $token = $token ?? $this->generateEmailVerificationToken();

        $this->insertVerificationTokenIntoDatabase($identifier, $token);

        if ($relative) {
            return '/account/email/verify?' . Arr::query([
                    'identifier' => sha1($identifier),
                    'token'      => $token,
                ]);
        }

        return url('/account/email/verify?' . Arr::query([
                'identifier' => sha1($identifier),
                'token'      => $token,
            ]));
    }

    public function generateEmailVerificationIdentifier(): string
    {
        return Str::uuid()->toString();
    }

    public function generateEmailVerificationToken(): string
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function insertVerificationTokenIntoDatabase(string $identifier, string $token): void
    {
        DB::table('email_verifications')->insert([
            'identifier' => $identifier,
            'token'      => $token,
            'expires_at' => now()->addHour(),
        ]);
    }

    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified(): void
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
