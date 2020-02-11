<?php

namespace app\records;

use app\components\db\Db;
use app\components\id\Id;

/**
 * Class User
 * @package app\records
 */
class User
{
    public $id;

    public $login;

    public $firstName;

    public $lastName;

    public $fullName;

    public $passwordHash;

    private function __construct(
        ?string $id,
        string $login,
        string $firstName,
        string $lastName,
        string $passwordHash
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->passwordHash = $passwordHash;
        $this->fullName = "{$firstName} {$lastName}";
    }

    public static function fromRegistration(
        string $login,
        string $firstName,
        string $lastName,
        string $hash
    ): self
    {
        return new self(
            null,
            $login,
            $firstName,
            $lastName,
            $hash
        );
    }

    public static function fromDb(array $record): self
    {
        return new self(
            $record['id'],
            $record['login'],
            $record['first_name'],
            $record['last_name'],
            $record['password_hash']
        );
    }

    public function save(): void
    {
        if ($this->id !== null) {
            Db::update('user',
                [
                    'login' => $this->login,
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'full_name' => $this->fullName,
                    'password_hash' => $this->passwordHash,
                ], [
                    'id' => $this->id
                ]);
        } else {
            Db::insert('user', [
                    'id' => Id::generate(),
                    'login' => $this->login,
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'full_name' => $this->fullName,
                    'password_hash' => $this->passwordHash,
                ]);
        }
    }

    public static function findByLogin(string $login): ?self
    {
        $record = Db::selectRow('user', [
            'login' => $login,
        ]);
        if (!is_array($record)) {
            return null;
        }
        return self::fromDb($record);
    }

    public function delete(): void
    {
        Db::delete('user', [
            'id' => $this->id,
        ]);
    }
}