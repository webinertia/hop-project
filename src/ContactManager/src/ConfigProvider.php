<?php

declare(strict_types=1);

namespace ContactManager;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Filter\ToNull;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

/**
 * The configuration provider for the ContactManager module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'authentication'     => $this->getAuthenticationConfig(),
            'dependencies'       => $this->getDependencies(),
            'form_elements'      => $this->getFormElementConfig(),
            'input_filters'      => $this->getFilterConfig(),
            'routes'             => $this->getRouteConfig(),
            'templates'          => $this->getTemplates(),
            'view_helpers'       => $this->getViewHelpers(),
            'view_helper_config' => $this->getViewHelperConfig(),
            'view_manager'       => $this->getViewManagerConfig(),
        ];
    }

    public function getAuthenticationConfig(): array
    {
        return [
            //'redirect' => '/contacts/dashboard',
            'username' => 'email',
            'password' => 'password',
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                Handler\ContactCrudHandler::class   => Handler\ContactCrudHandlerFactory::class,
                Handler\ContactHandler::class       => Handler\ContactHandlerFactory::class,
                Handler\ListHandler::class          => Handler\ListHandlerFactory::class,
                Handler\DashboardHandler::class     => Handler\DashboardHandlerFactory::class,
                Handler\LandingHandler::class       => Handler\LandingHandlerFactory::class,
                Repository\ContactRepository::class => Repository\ContactRepositoryFactory::class,
                Repository\ListRepository::class    => Repository\ListRepositoryFactory::class,
            ],
        ];
    }

    public function getFilterConfig(): array
    {
        return [
            'factories' => [
                Filter\Contact::class => InvokableFactory::class
            ],
        ];
    }

    public function getFormElementConfig(): array
    {
        return [
            'factories' => [
                Form\Contact::class => Form\ContactFactory::class,
            ],
        ];
    }

    public function getRouteConfig(): array
    {
        return [
            [
                'path'       => '/cm/dashboard',
                'name'       => 'cm.dashboard',
                'middleware' => [
                    AuthenticationMiddleware::class,
                    Handler\DashboardHandler::class
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST]
            ],
            [
                'path'       => '/cm/get/contact/{id:\d+}[/{list_id:\d+}]',
                'name'       => 'cm.get.contact',
                'middleware' => [
                    AuthenticationMiddleware::class,
                    Handler\ContactHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET],
            ],
            [
                'path'       => '/cm/contact[/{list_id:\d+}[/{id:\d+}]]',
                'name'       => 'cm.contact',
                'middleware' => [
                    AuthenticationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\ContactCrudHandler::class
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST, Http::METHOD_PUT, Http::METHOD_PATCH, Http::METHOD_DELETE],
            ],
            [
                'path'       => '/cm/list',
                'name'       => 'cm.list',
                'middleware' => [
                    AuthenticationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\ListHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'cm'          => [__DIR__ . '/../templates/contact-manager'],
                'cm-partials' => [__DIR__ . '/../templates/partials'],
            ],
        ];
    }

    public function getViewHelpers(): array
    {
        return [
            'aliases'   => [
                'bodyClass' => View\Helper\BodyClassHelper::class,
            ],
            'factories' => [
                View\Helper\BodyClassHelper::class => View\Helper\BodyClassHelperFactory::class
            ],
        ];
    }

    public function getViewHelperConfig(): array
    {
        return [
            'doctype' => 'HTML5',
            'body_class' => 'layout-fixed sidebar-expand-lg bg-body-tertiary',
        ];
    }

    public function getViewManagerConfig(): array
    {
        return [
            'base_path' => '/',
        ];
    }
}
