<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ContactRepository;
use ContactManager\Repository\ListRepository;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class DashboardHandlerFactory
{
    public function __invoke(ContainerInterface $container) : DashboardHandler
    {
        return new DashboardHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ContactRepository::class),
            $container->get(ListRepository::class)
        );
    }
}
