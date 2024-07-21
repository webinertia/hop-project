<?php

declare(strict_types=1);

namespace ContactManager\Repository;

use Axleus\Db\AbstractRepository;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;

final class ContactRepository extends AbstractRepository
{
    public function findAllByUserId(int $userId)
    {
        /** @var Select */
        $select = new Select($this->gateway->getTable());
        //$select->columns(['id', 'user_id', 'first_name', 'list_id']);
        $where = new Where();
        $where->equalTo('user_id', $userId);
        $select->join(
            ['l' => 'lists'],
            'l.id = list_id'
        );
        //$select->group('list_id');
        $select->order(['list_id ASC']);
        $select->where($where);
        $result = $this->gateway->selectWith($select);
        return $result->toArray();
    }
}
