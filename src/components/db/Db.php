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

    private static $pdo;

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
        if (self::$pdo === null) {
            $config = self::getConfig();
            $dsn = "mysql:host={$config->dbHost};port={$config->dbPort};dbname={$config->dbName}";
            $pdo = new PDO($dsn, $config->dbUsername, $config->dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo = $pdo;
        }

        return self::$pdo;
    }

    private static function getConfig(): Config
    {
        if (self::$config === null) {
            throw new \LogicException('Для работы компонента БД требуется передать ему объект конфигурации приложения');
        }
        return self::$config;
    }

    public static function selectRow(string $table, array $where = []): ?array
    {
        $wherePart = self::buildWhere($where);
        $row = self::getPdo()
            ->query("SELECT * FROM `{$table}` {$wherePart}")
            ->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            // Если результат пустой, PDO возвращает "false".
            return null;
        }
        return $row;
    }

    public static function delete(string $table, array $where = []): void
    {
        $wherePart = self::buildWhere($where);
        self::exec("DELETE FROM {$table} {$wherePart}", []);
    }

    private static function buildWhere(array $where): string
    {
        $wherePart = '';

        if (count($where) !== 0) {
            $conditions = [];
            foreach ($where as $key => $value) {
                if (is_int($value)) {
                    $quotedValue = $value;
                }
                if (is_string($value)) {
                    $quotedValue = self::getPdo()->quote($value);
                }
                if (!is_int($value) && !is_string($value)) {
                    $dumpValue = print_r($value, true);
                    throw new \LogicException("Неподдерживаемый тип данных: {$dumpValue}");
                }
                $conditions[]= "`{$key}`={$quotedValue}";
            }
            $conditionsCombined = implode(' AND ', $conditions);
            $wherePart = "WHERE {$conditionsCombined}";
        }

        return $wherePart;
    }
}