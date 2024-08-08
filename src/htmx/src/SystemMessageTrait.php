<?php

declare(strict_types=1);

namespace Htmx;

use function json_encode;

trait SystemMessageTrait
{
    protected function systemMessage(array $data): string
    {
        if (! isset($data['event'])) {
            $data['event'] = 'systemMessage';
        }
        $event = $data['event'];
        unset($data['event']);
        $message = [$event => $data];
        return json_encode($message);
    }
}
