<?php

declare(strict_types=1);

namespace ContactManager\Repository;

use Axleus\Db\AbstractRepository;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Hydrator\HydratorInterface;

final class ListRepository extends AbstractRepository
{
    public function findUserList(int $userId, int $listId)
    {
        $select = $this->gateway->getSql();
        $where  = new Where();

    }
}
