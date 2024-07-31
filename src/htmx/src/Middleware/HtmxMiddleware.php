<?php

declare(strict_types=1);

namespace Htmx\Middleware;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HtmxMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // $response = $handler->handle($request);
        // reset the isAjax to true to support Axleus / Webinertia components
        if ($request->hasHeader('HX-Request')) {
            $request = $request->withAttribute('isAjax', true);
            $this->template->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'layout',
                false
            );
        }
        return $handler->handle($request);
    }
}
