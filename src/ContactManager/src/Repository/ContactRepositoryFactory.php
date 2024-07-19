<?php

declare(strict_types=1);

namespace ContactManager\Repository;

use Axleus\Db\TableGateway;
use ContactManager\Entity\ContactEntity;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ReflectionHydrator;
use Psr\Container\ContainerInterface;

final class ContactRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ContactRepository
    {
        return new ContactRepository(
            new TableGateway(
                'contacts',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    new ReflectionHydrator(),
                    new ContactEntity()
                )
            )
        );
    }
}
