<?php

declare(strict_types=1);

namespace ContactManager\Entity;

use Axleus\Db\EntityInterface;
use Laminas\Stdlib\ArrayObject;

final class ListEntity extends ArrayObject implements EntityInterface
{
    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }
}
