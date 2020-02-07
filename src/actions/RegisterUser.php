<?php

namespace app\actions;

use app\records\User;

/**
 * Class RegisterUser
 * @package app\actions
 */
class RegisterUser
{
    public function __construct(
        string $login,
        string $firstName,
        string $lastName,
        string $password
    )
    {
        $user = User::fromRegistration($login, $firstName, $lastName, $this->hash($password));
        $user->save();
    }

    /**
     * @param string $password
     * @return string
     */
    private function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}