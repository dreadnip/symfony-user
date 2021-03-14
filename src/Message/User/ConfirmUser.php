<?php

namespace App\Message\User;

use App\Entity\User\User;

class ConfirmUser
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}