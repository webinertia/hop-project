<?php

declare(strict_types=1);

namespace ContactManager\View\Helper;

use Psr\Container\ContainerInterface;

final class BodyClassHelperFactory
{
    public function __invoke(ContainerInterface $container): BodyClassHelper
    {
        $helperConfig = $container->get('config')['view_helper_config'];
        $bodyClass    = $helperConfig['body_class'];
        return new BodyClassHelper($bodyClass);
    }
}
