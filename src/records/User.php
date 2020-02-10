<?php

namespace app\records;

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
}