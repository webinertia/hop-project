<?php

declare(strict_types=1);

namespace ContactManager;

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
    public function __invoke() : array
    {
        return [
            'authentication'     => $this->getAuthenticationConfig(),
            'dependencies'       => $this->getDependencies(),
            'templates'          => $this->getTemplates(),
            'view_helpers'       => $this->getViewHelpers(),
            'view_helper_config' => $this->getViewHelperConfig(),
            'view_manager'       => $this->getViewManagerConfig(),
        ];
    }

    public function getAuthenticationConfig(): array
    {
        return [
            'redirect' => '/contacts/dashboard',
            'username' => 'email',
            'password' => 'password',
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'contact-manager'    => [__DIR__ . '/../templates/'],
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
