<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Form\Contact;
use ContactManager\Repository\ContactRepository;
use Laminas\Form\FormElementManager;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ContactHandlerFactory
{
    public function __invoke(ContainerInterface $container): ContactHandler
    {
        $manager = $container->get(FormElementManager::class);
        return new ContactHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ContactRepository::class),
            $manager->get(Contact::class),
            $container->get(UrlHelper::class)
        );
    }
}
