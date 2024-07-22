<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ContactRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;

class CreateContactHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private ContactRepository $contactRepository
    ) {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $requestBody = $request->getParsedBody();
        try {
            if (isset($requestBody['list_id'])) {
                $currentUser            = $request->getAttribute(UserInterface::class)->getDetails();
                $requestBody['user_id'] = $currentUser['id'];
                $contact                = $this->contactRepository->save($requestBody);
                return new HtmlResponse(
                    $this->renderer->render('partial::sortable-contact', ['contact' => $contact->getArrayCopy()])
                );
            }
        } catch (\Throwable $th) {
            return new JsonResponse(['success' => false], 500);
        }
    }
}
