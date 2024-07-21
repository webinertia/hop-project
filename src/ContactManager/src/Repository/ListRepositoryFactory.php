<?php

declare(strict_types=1);

namespace ContactManager\Repository;

use Axleus\Db\TableGateway;
use ContactManager\Entity\ListEntity;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ArraySerializableHydrator;
use Psr\Container\ContainerInterface;

final class ListRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ListRepository
    {
        $hydrator = new ArraySerializableHydrator();
        $repo = new ListRepository(
            new TableGateway(
                'lists',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    $hydrator,
                    new ListEntity([], ListEntity::ARRAY_AS_PROPS)
                )
            )
        );
        $repo->setHydrator($hydrator);
        return $repo;
    }
}
