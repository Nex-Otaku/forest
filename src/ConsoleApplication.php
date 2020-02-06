<?php

namespace app;

use app\components\console\Console;

/**
 * Class ConsoleApplication
 * @package app
 */
class ConsoleApplication
{
    /**
     * ConsoleApplication constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
    }

    /**
     * @return int
     */
    public function run(): int
    {
        $console = new Console();
        [$route, $params] = $console->resolve();
        $this->runAction($route, $params);
        return 0;
    }

    public function runAction(string $route, array $params = []): void
    {
        if ($route === 'hello') {
            echo "Hello World!\n";
        }
    }
}