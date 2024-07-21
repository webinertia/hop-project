<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ListRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Template\TemplateRendererInterface;

class CreateListHandler implements RequestHandlerInterface
{

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ListRepository $listRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        if (isset($requestBody['list_name']) && $this->listRepository->save($requestBody)) {
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse($this->renderer->render(
            'contact-manager::create-list',
            [] // parameters to pass to template
        ));
    }
}
