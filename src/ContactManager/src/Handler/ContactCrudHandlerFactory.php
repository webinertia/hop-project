<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Form\Contact;
use ContactManager\Filter\Contact as ContactFilter;
use ContactManager\Repository\ContactRepository;
use Laminas\Form\FormElementManager;
use Laminas\InputFilter\InputFilterPluginManager;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ContactCrudHandlerFactory
{
    public function __invoke(ContainerInterface $container): ContactCrudHandler
    {
        $manager = $container->get(FormElementManager::class);
        $filterManager = $container->get(InputFilterPluginManager::class);
        return new ContactCrudHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ContactRepository::class),
            $manager->get(Contact::class),
            $filterManager->get(ContactFilter::class),
            $container->get(UrlHelper::class)
        );
    }
}
