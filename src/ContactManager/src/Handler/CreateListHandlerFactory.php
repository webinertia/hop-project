<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ListRepository;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class CreateListHandlerFactory
{
    public function __invoke(ContainerInterface $container): CreateListHandler
    {
        return new CreateListHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ListRepository::class)
        );
    }
}
