<?php

declare(strict_types=1);

namespace ContactManager;

use Fig\Http\Message\RequestMethodInterface as Http;
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
                Handler\CreateContactHandler::class => Handler\CreateContactHandlerFactory::class,
                Handler\CreateListHandler::class    => Handler\CreateListHandlerFactory::class,
                Handler\DashboardHandler::class     => Handler\DashboardHandlerFactory::class,
                Handler\LandingHandler::class       => Handler\LandingHandlerFactory::class,
                Repository\ContactRepository::class => Repository\ContactRepositoryFactory::class,
                Repository\ListRepository::class    => Repository\ListRepositoryFactory::class,
            ],
        ];
    }

    public function getRouteConfig(): array
    {
        return [
            [
                'path'       => '/contacts/dashboard',
                'name'       => 'cm.dashboard',
                'middleware' => [
                    Handler\DashboardHandler::class
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST]
            ],
            [
                'path'       => '/contacts/new[/{list_id:\d+}]',
                'name'       => 'cm.create.contact',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    Handler\CreateContactHandler::class
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
            [
                'path'       => '/contacts/create/list',
                'name'       => 'cm.create.list',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    Handler\CreateListHandler::class,
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
                'contact-manager'          => [__DIR__ . '/../templates/contact-manager'],
                'contact-manager-partials' => [__DIR__ . '/../templates/partials'],
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
