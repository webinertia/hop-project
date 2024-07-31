<?php

declare(strict_types=1);

namespace Htmx;

use Laminas\ServiceManager\Factory\InvokableFactory;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies'       => $this->getDependencies(),
            'view_helpers'       => $this->getViewHelpers(),
            'view_helper_config' => $this->getViewHelperConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Middleware\HtmxMiddleware::class => Middleware\HtmxMiddlewareFactory::class,
            ],
        ];
    }

    public function getViewHelpers(): array
    {
        return [
            'aliases'   => [
                'htmxTag' => View\Helper\HtmxTag::class,
            ],
            'factories' => [
                View\Helper\HtmxTag::class => InvokableFactory::class
            ],
        ];
    }

    public function getViewHelperConfig(): array
    {
        return [];
    }
}
