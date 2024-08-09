<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use ContactManager\Filter\Contact as ContactFilter;
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
/**
 * todo: Introduce Authorization
 * @package ContactManager\Handler
 */
class ContactCrudHandler implements RequestHandlerInterface
{
    use SystemMessageTrait;

    private array $headers;

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ContactRepository $contactRepository,
        private Contact $form,
        private ContactFilter $inputFilter,
        private UrlHelper $url
    ) {
        $this->headers = [
            'HX-Trigger' => $this->systemMessage(['level' => 'success', 'message' => 'Contact Saved!']), // these could be translated
            'HX-Success' => 'true', // closes the modal, this is a custom header that follows the HX usage
        ];
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // handle supported Http Method types, proxy to the correct handler method
        return match($request->getMethod()) {
            Http::METHOD_GET    => $this->handleGet($request),
            Http::METHOD_POST   => $this->handlePost($request),
            Http::METHOD_PUT    => $this->handlePut($request),
            Http::METHOD_DELETE => $this->handleDelete($request),
            default => new EmptyResponse(405), // defaults to a method not allowed
        };
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        $params      = $routeResult->getMatchedParams();
        $query       = $request->getQueryParams();

        // set our required attributes on the form instance
        $this->form->setAttributes([
            'hx-post' => $this->url->generate('cm.contact'),
        ]);
        $this->form->setData($params); // sets the list_id in the form instance

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
                $this->form->setAttributes([
                    'hx-post'   => $this->url->generate('cm.contact'), // set this on the form instance so it knows where to post
                ]);
                $this->form->setData($requestBody); // set form data to validate
                if (! $this->form->isValid()) { // validates the form fields against the filter/validator chain that is attached to the form instance
                    // really not needed but its an example of how to trigger a system message on some error condition
                    $this->headers['HX-Trigger'] = $this->systemMessage(['level' => 'warning', 'message' => 'Validation Failure.']);
                    $this->headers['HX-Success'] = 'false';
                    return new HtmlResponse(
                        $this->renderer->render(
                            'cm-partials::contact-modal',
                            ['form' => $this->form]
                        ),
                        422, // important note, htmx is configured currently to swap all responses regardless of code
                        $this->headers
                    );
                }
                // get the current user from the request instance
                $currentUser            = $request->getAttribute(UserInterface::class)->getDetails();
                // push the current user into the request body for save
                $requestBody['user_id'] = $currentUser['id'];
                // persist request
                $contact                = $this->contactRepository->save($requestBody);
                return new HtmlResponse(
                    $this->renderer->render(
                        'cm-partials::contact-modal', // render the contact-modal, see the template files for the out-of-band swap
                        ['form' => $this->form, 'contact' => $contact]
                    ),
                    201, // set the correct response code
                    $this->headers
                );
            }
        } catch (\Throwable $th) {
            // todo: improve exception handling
        }
    }

    // Handle updating of a contact
    public function handlePut(ServerRequestInterface $request): ResponseInterface
    {
        // todo: wrap in try catch
        // todo: query contact
        // todo: run update
        // todo: return correct response.
        $requestBody = $request->getParsedBody();
        $this->headers['HX-Trigger'] = $this->systemMessage(['level' => 'success', 'message' => 'Updated Successfully!']);
        $this->inputFilter->setData($requestBody);
        $this->inputFilter->isValid();
        $params = $this->inputFilter->getValues(); // we now have either ints or nulls only
        return new EmptyResponse(
            204,
            $this->headers
        );
    }

    // Handle contact deletion
    public function handleDelete(ServerRequestInterface $request): ResponseInterface
    {

    }
}
