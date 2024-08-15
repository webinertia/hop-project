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
        $select = new Select(['c' => $this->gateway->getTable()]);
        $select->columns(['id', 'user_id', 'first_name', 'last_name', 'email', 'list_id']);
        $where = new Where();
        $where->equalTo('user_id', $userId);
        // $select->join(
        //     ['l' => 'lists'],
        //     'l.id = list_id'
        // );
        $select->join(
            ['l' => 'lists'],
            'c.list_id = l.id',
            ['list_name'],
            Select::JOIN_LEFT_OUTER
        );
        //$select->group('list_id');
        $select->order(['list_id ASC']);
        $select->where($where);
        $result = $this->gateway->selectWith($select);
        return $result->toArray();
    }
}
