<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ContactRepository;
use Htmx\SystemMessageTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;

class ContactHandler implements RequestHandlerInterface
{
    use SystemMessageTrait;

    private array $headers;

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ContactRepository $contactRepository,
        private UrlHelper $url
    ) {
        $this->headers = [
            'HX-Trigger' => $this->systemMessage(['level' => 'success', 'message' => 'Contact Saved!']),
            'HX-Success' => 'true', // closes the modal
        ];
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // todo: wrap this is a try catch and handle errors with a systemMessage notification etc
        $routeResult = $request->getAttribute(RouteResult::class);
        $params      = $routeResult->getMatchedParams();
        $query       = $request->getQueryParams();
        $contact     = $this->contactRepository->findOneBy('id', $params['id']);
        // return response
        return new HtmlResponse(
            $this->renderer->render(
                'cm-partials::sortable-contact',
                [
                    'contact' => $contact,
                ]
            ),
            200,
            $this->headers
        );
    }
}
