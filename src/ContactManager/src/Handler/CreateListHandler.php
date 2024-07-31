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
        try {
            if (isset($requestBody['list_name'])) {
                $list = $this->listRepository->save($requestBody);
                return new HtmlResponse(
                    $this->renderer->render('contact-manager-partials::sortable-list', ['list' => $list->getArrayCopy()])
                );
            }
        } catch (\Throwable $th) {
            return new JsonResponse(['success' => false], 500);
        }
    }
}
