<?php

namespace app\components\session;

/**
 * Class Session
 * @package app\components\session
 */
class Session
{
    public function bootstrap(): void
    {
        if (session_id() !== '') {
            return;
        }

        @session_start();
    }
}