<?php

namespace app;

use app\components\console\Console;
use app\components\console\ShellCommand;

/**
 * Class ConsoleApplication
 * @package app
 */
class ConsoleApplication
{
    /** @var Config */
    private $config;

    /**
     * ConsoleApplication constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
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
            var_dump($params);
            echo "Hello World!\n";
            return;
        }
        if ($route === 'hello2') {

            echo "Hello World2!\n";
            return;
        }
        if ($route === 'kiss') {
            $person = $params[0];
            echo "You kissed {$person}\n";
            return;
        }

        if ($route === 'install') {
            $this->installDb();
            return;
        }

        echo "Not found action: {$route}\n";
    }

    private function installDb(): void
    {
        $params = [
            ':user' => $this->config->dbUsername,
            ':host' => $this->config->dbHost,
            ':port' => $this->config->dbPort,
            ':db' => $this->config->dbName,
            ':dumpfile' => $this->getDumpFilePath(),
        ];
        $passwordOption = '';
        if ($this->config->dbPassword !== '') {
            $passwordOption = ' -p :password';
            $params[':password'] = $this->config->dbPassword;
        }
        $this->shellExec("mysql -u :user -h :host --port :port {$passwordOption} :db < :dumpfile", $params);
    }

    /**
     * @return string
     */
    private function getDumpFilePath(): string
    {
        // PROJECT_ROOT/sql/schema.sql
        return __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'sql'
            . DIRECTORY_SEPARATOR . 'schema.sql';
    }

    /**
     * @param string $command
     * @param array $params
     */
    private function shellExec(string $command, array $params): void
    {
        $parsedCommand = ShellCommand::applyParams($command, $params);
        echo "> {$parsedCommand}\n";
        $out = ShellCommand::run($command, $params);
        foreach ($out as $line) {
            echo "{$line}\n";
        }
    }
}