<?php

declare(strict_types=1);

namespace ContactManager\Entity;

use Axleus\Db\EntityInterface;

final class ContactEntity implements EntityInterface
{

    public function getId(): ?int { }

    public function getArrayCopy() { }

}
