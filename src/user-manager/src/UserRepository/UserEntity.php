<?php

declare(strict_types=1);

namespace UserManager\UserRepository;

use ArrayObject;
use Axleus\Db;
use Mezzio\Authentication\UserInterface;

final class UserEntity extends ArrayObject implements Db\EntityInterface, UserInterface
{
    use Db\EntityTrait;

    public function __construct(
    ) {
        parent::__construct([], self::ARRAY_AS_PROPS);
    }

    public function getIdentity(): string
    {
        return $this->getEmail();
    }

    public function getRoles(): iterable
    {
        return (array) $this->offsetGet('role_id');
    }

    public function getDetail(string $name, $default = null)
    {
        return $this->offsetGet($name) ?? $default;
    }

    public function getDetails(): array
    {
        return $this->getArrayCopy();
    }

    public function setId(int $id): void
    {
        $this->offsetSet('id', $id);
    }

    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }

    public function setEmail(string $email): void
    {
        $this->offsetSet('email', $email);
    }

    public function getEmail(): string
    {
        return $this->offsetGet('email');
    }

    public function setPassword(string $password): void
    {
        $this->offsetSet('password', $password);
    }

    public function getPassword(): ?string
    {
        return $this->offsetGet('password');
    }
}
