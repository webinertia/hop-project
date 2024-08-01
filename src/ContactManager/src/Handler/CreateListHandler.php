<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use Axleus\Db\EntityInterface;
use ContactManager\Repository\ListRepository;
use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
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
        $method = $request->getMethod();
        return match($request->getMethod()) {
            Http::METHOD_GET  => $this->handleGet($request),
            Http::METHOD_POST => $this->handlePost($request),
            default => new EmptyResponse(405),
        };
    }

    private function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->renderer->render('contact-manager-partials::create-list-modal')
        );
    }

    private function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();
        try {
            if (isset($requestBody['list_name'])) {
                $list = $this->listRepository->save($requestBody);
                if (! $list instanceof EntityInterface) {
                    // throw exception
                }
                return new HtmlResponse(
                    $this->renderer->render('contact-manager-partials::sortable-list', ['list' => $list->getArrayCopy()]),
                    200,
                    ['HX-Success' => 'true']
                );
            }
        } catch (\Throwable $th) {
            return new HtmlResponse(['success' => false], 500);
        }
    }
}
