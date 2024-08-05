<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Form\Contact;
use ContactManager\Repository\ContactRepository;
use Fig\Http\Message\RequestMethodInterface as Http;
use Htmx\SystemMessageTrait;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class ContactHandler implements RequestHandlerInterface
{
    use SystemMessageTrait;

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ContactRepository $contactRepository,
        private Contact $form,
        private UrlHelper $url
    ) {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return match($request->getMethod()) {
            Http::METHOD_GET    => $this->handleGet($request),
            Http::METHOD_POST   => $this->handlePost($request),
            Http::METHOD_PUT    => $this->handlePut($request),
            Http::METHOD_DELETE => $this->handleDelete($request),
            default => new EmptyResponse(405),
        };
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        $params      = $routeResult->getMatchedParams();

        // set our required attributes on the form instance
        $this->form->setAttributes([
            'class'     => 'modal-body',
            'hx-post'   => $this->url->generate('cm.contact'),
            'hx-target' => '#list_' . $params['list_id'] . ' .card-body .connectedSortable',
            'hx-swap'   => 'beforeend',
        ]);
        $this->form->setData($params);

        // return our response which will render our form
        return new HtmlResponse(
            $this->renderer->render(
                'cm-partials::contact-modal',
                ['form' => $this->form]
            )
        );
    }

    public function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        // get the parsed request body params
        $requestBody = $request->getParsedBody();
        try {
            if (isset($requestBody['list_id'])) {
                // get the current user from the request instance
                $currentUser            = $request->getAttribute(UserInterface::class)->getDetails();
                // push the current user into the request body for save
                $requestBody['user_id'] = $currentUser['id'];
                // persist request
                $contact                = $this->contactRepository->save($requestBody);

                return new HtmlResponse(
                    $this->renderer->render(
                        'cm-partials::sortable-contact',
                        ['contact' => $contact->getArrayCopy()]
                    ),
                    200,
                    [
                        'HX-Trigger' => $this->formatMessage(['level' => 'success', 'message' => 'Contact Saved!']),
                        'HX-Success' => 'true',
                    ]
                );
            }
        } catch (\Throwable $th) {
            //return new JsonResponse(['success' => false], 500);
        }
    }

    // Handle updating of a contact
    public function handlePut(ServerRequestInterface $request): ResponseInterface
    {

    }

    // Handle contact deletion
    public function handleDelete(ServerRequestInterface $request): ResponseInterface
    {

    }
}
