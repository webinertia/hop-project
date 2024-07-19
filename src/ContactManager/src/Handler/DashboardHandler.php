<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;

class DashboardHandler implements RequestHandlerInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $currentUser = $request->getAttribute(UserInterface::class);
        if ($currentUser->getIdentity() === 'guest') {
            return new RedirectResponse('/');
        }
        return new HtmlResponse($this->renderer->render(
            'contact-manager::dashboard',
            [] // parameters to pass to template
        ));
    }
}
