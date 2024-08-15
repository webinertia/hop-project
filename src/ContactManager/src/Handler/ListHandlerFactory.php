<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ListRepository;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListHandler
    {
        return new ListHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ListRepository::class)
        );
    }
}
