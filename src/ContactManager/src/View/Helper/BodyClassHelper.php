<?php

declare(strict_types=1);

namespace ContactManager\View\Helper;

final class BodyClassHelper
{
    public function __construct(
        private ?string $bodyClass = null
    ) {
    }

    public function __invoke(?string $bodyClass = null)
    {
        if ($bodyClass !== null) {
            $this->bodyClass = $bodyClass;
        }
        return $this->bodyClass;
    }
}
