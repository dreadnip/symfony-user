<?php

namespace App\DataTransferObject\User;

use App\Validator\User\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class UserDataTransferObject
{
    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     * @UniqueEmail()
     */
    public string $email;

    /**
     * @Assert\NotBlank()
     */
    public string $password;
}