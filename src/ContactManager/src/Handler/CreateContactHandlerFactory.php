<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ContactRepository;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class CreateContactHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CreateContactHandler
    {
        return new CreateContactHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ContactRepository::class)
        );
    }
}
