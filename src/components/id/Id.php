<?php

namespace app\components\id;

use Ramsey\Uuid\Uuid as Ruuid;

/**
 * Class Id
 * @package app\components\id
 */
class Id
{
    public static function generate(): string
    {
        return Ruuid::uuid4();
    }
}