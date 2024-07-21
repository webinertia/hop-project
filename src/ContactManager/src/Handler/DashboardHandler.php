<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Repository\ContactRepository;
use ContactManager\Repository\ListRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;

class DashboardHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private ContactRepository $contactRepository,
        private ListRepository $listRepository
    ) {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $currentUser = $request->getAttribute(UserInterface::class);
        if ($currentUser->getIdentity() === 'guest') {
            return new RedirectResponse('/');
        }
        $userDetails = $currentUser->getDetails();
        $contacts    = $this->contactRepository->findAllByUserId($userDetails['id']);
        $lists       = $this->listRepository->findAll()->toArray();

        return new HtmlResponse($this->renderer->render(
            'contact-manager::dashboard',
            [
                'list'     => $lists,
                'contacts' => $contacts
            ] // parameters to pass to template
        ));
    }
}
