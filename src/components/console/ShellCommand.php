<?php

namespace app\components\console;

/**
 * Class ShellCommand
 * @package app\components\console
 */
class ShellCommand
{
    /**
     * @param string $command
     * @param array $params
     * @return array
     */
    public static function run(string $command, array $params = []): array
    {
        $command = self::applyParams($command, $params);
        return self::executeCommand($command);
    }

    /**
     * @param string $command
     * @param array $params
     * @return string
     */
    public static function applyParams(string $command, array $params): string
    {
        if (count($params) === 0) {
            return $command;
        }
        return self::replace($command, $params);
    }

    /**
     * @param string $text
     * @param array $replacements
     * @return string
     */
    private static function replace(string $text, array $replacements): string
    {
        $result = $text;
        foreach ($replacements as $search => $replacement) {
            $result = str_replace($search, $replacement, $result, $replacesCount);
            if ($replacesCount === 0) {
                throw new \LogicException("Не найден ключ \"{$search}\" для замены в строке \"{$result}\"");
            }
        }
        return $result;
    }

    /**
     * @param string $command
     * @return array
     */
    private static function executeCommand(string $command): array
    {
        $output = [];
        exec($command, $output);
        return $output;
    }
}