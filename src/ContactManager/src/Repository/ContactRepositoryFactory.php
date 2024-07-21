<?php

declare(strict_types=1);

namespace ContactManager\Repository;

use Axleus\Db\TableGateway;
use ContactManager\Entity\ContactEntity;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ArraySerializableHydrator;
use Psr\Container\ContainerInterface;

final class ContactRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ContactRepository
    {
        $hydrator = new ArraySerializableHydrator();
        $repo = new ContactRepository(
            new TableGateway(
                'contacts',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    $hydrator,
                    new ContactEntity([], ContactEntity::ARRAY_AS_PROPS)
                )
            )
        );
        $repo->setHydrator($hydrator);
        return $repo;
    }
}
