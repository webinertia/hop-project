<?php

declare(strict_types=1);

namespace ContactManager\Form;

use Psr\Container\ContainerInterface;

final class ContactFactory
{
    public function __invoke(ContainerInterface $container): Contact
    {
        return new Contact();
    }
}
