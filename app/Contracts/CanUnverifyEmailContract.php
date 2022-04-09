<?php

namespace App\Contracts;

interface CanUnverifyEmailContract
{
    public function unmarkEmailAsVerified():void;
}
