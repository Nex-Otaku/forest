<?php

namespace app\components\db;

use app\Config;
use PDO;

/**
 * Class Db
 * @package app\components\db
 */
class Db
{
    private static $config;

    public static function setConfig(Config $config)
    {
        self::$config = $config;
    }

    public static function insert(string $table, array $fields): void
    {
        $pdoSet = self::pdoSet(array_keys($fields));
        self::exec("INSERT INTO {$table} SET {$pdoSet}", $fields);
    }

    public static function update(string $table, array $fields, array $where = []): void
    {
        // TODO
    }

    private static function pdoSet(array $fieldNames): string
    {
        if (count($fieldNames) === 0) {
            throw new \LogicException('Массив полей не может быть пустым');
        }
        $set = '';
        foreach ($fieldNames as $fieldName) {
            $escapedField = str_replace('`', '``', $fieldName);
            $set .= "`{$escapedField}`=:{$fieldName}, ";
        }
        return substr($set, 0, -2);
    }

    private static function exec(string $sql, array $parameters): void
    {
        $pdo = self::getPdo();
        $statement = $pdo->prepare($sql);
        $statement->execute($parameters);
    }

    private static function getPdo(): PDO
    {
        $config = self::getConfig();
        $dsn = "mysql:host={$config->dbHost};port={$config->dbPort};dbname={$config->dbName}";
        return new PDO($dsn, $config->dbUsername, $config->dbPassword);
    }

    private static function getConfig(): Config
    {
        if (self::$config === null) {
            throw new \LogicException('Для работы компонента БД требуется передать ему объект конфигурации приложения');
        }
        return self::$config;
    }
}