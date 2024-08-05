<?php

declare(strict_types=1);

namespace Htmx;

use function json_encode;

trait SystemMessageTrait
{
    protected function formatMessage(array $data): string
    {
        $message = ['systemMessage' => $data];
        return json_encode($message);
    }
}
