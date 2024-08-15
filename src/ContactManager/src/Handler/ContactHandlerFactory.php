<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ContactRepository;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ContactHandlerFactory
{
    public function __invoke(ContainerInterface $container): ContactHandler
    {
        return new ContactHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ContactRepository::class),
            $container->get(UrlHelper::class)
        );
    }
}
