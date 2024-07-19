<?php

declare(strict_types=1);

namespace App\UserRepository;

use Axleus\Db;
use Cm\Storage\PrimaryKey;
use Cm\Storage\Schema;
use Laminas\Db\RowGateway\RowGateway;

final class UserEntity implements Db\EntityInterface
{
    use Db\EntityTrait;

    public function __construct(
        private ?int $id = null,
        private ?string $email = null,
        #[\SensitiveParameter]
        private ?string $password = null
    ) {
        // parent::__construct(PrimaryKey::User->value, Schema::User->value, )
    }

    public function getArrayCopy() { }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
