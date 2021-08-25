<?php

namespace App\Events;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Queue\SerializesModels;

class Unverified
{
    use SerializesModels;

    /**
     * The unverified user.
     *
     * @var MustVerifyEmail
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param MustVerifyEmail $user
     * @return void
     */
    public function __construct(MustVerifyEmail $user)
    {
        $this->user = $user;
    }
}
