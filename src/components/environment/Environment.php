<?php

namespace app\components\environment;

/**
 * Class Environment
 * @package app\components\environment
 */
class Environment
{
    private static function getRootFolder(): string
    {
        return __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..';
    }

    public static function getCacheFolder(): string
    {
        return self::getRootFolder()
            . DIRECTORY_SEPARATOR . 'var'
            . DIRECTORY_SEPARATOR . 'cache';
    }

    public static function getTemplatesFolder(): string
    {
        return self::getRootFolder()
            . DIRECTORY_SEPARATOR . 'templates';
    }
}