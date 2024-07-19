<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class LandingHandlerFactory
{
    public function __invoke(ContainerInterface $container) : LandingHandler
    {
        return new LandingHandler($container->get(TemplateRendererInterface::class));
    }
}
