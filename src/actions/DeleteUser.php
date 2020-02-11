<?php

namespace app\actions;

use app\records\User;

/**
 * Class DeleteUser
 * @package app\actions
 */
class DeleteUser
{
    private $login;

    public function __construct(string $login)
    {
        $this->login = $login;
    }

    public function execute(): void
    {
        $user = User::findByLogin($this->login);
        if ($user === null) {
            throw new \LogicException();
            return;
        }
        $user->delete();
    }
}