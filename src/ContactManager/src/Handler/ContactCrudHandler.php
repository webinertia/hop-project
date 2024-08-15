<?php

declare(strict_types=1);

namespace ContactManager\Handler;

use Axleus\Db\EntityInterface;
use ContactManager\Filter\Contact as ContactFilter;
use ContactManager\Form\Contact;
use ContactManager\Repository\ContactRepository;
use Fig\Http\Message\RequestMethodInterface as Http;
use Htmx\HtmxTriggerTrait;
use Htmx\ResponseHeaders as HtmxHeader;
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
    use HtmxTriggerTrait;

    private int $code = 200;
    private string $modalTitle = '';

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ContactRepository $contactRepository,
        private Contact $form,
        private ContactFilter $inputFilter,
        private UrlHelper $url
    ) {
        $this->headers = [
            'HX-Success' => 'true', // closes the modal, this is a custom header that follows the HX usage
        ];
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // handle supported Http Method types, proxy to the correct handler method
        return match($request->getMethod()) {
            Http::METHOD_GET    => $this->handleGet($request),
            Http::METHOD_POST   => $this->handlePost($request),
            Http::METHOD_PUT    => $this->handlePut($request),
            Http::METHOD_PATCH  => $this->handlePatch($request),
            Http::METHOD_DELETE => $this->handleDelete($request),
            default => new EmptyResponse(405), // defaults to a method not allowed
        };
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $modalTitle = '';
        $routeResult = $request->getAttribute(RouteResult::class);
        $params      = $routeResult->getMatchedParams();
        $query       = $request->getQueryParams();

        if (! isset($params['id'])) { // if contact id is not set then its post
            // set our required attributes on the form instance
            $this->form->setAttributes([
                'hx-post' => $this->url->generate('cm.contact'),
            ]);
            $this->form->setData($params); // sets the list_id in the form instance
            $this->modalTitle = 'New Contact';
        }

        if (isset($params['id'])) {
             // set our required attributes on the form instance
             $this->form->setAttributes([
                'hx-put' => $this->url->generate('cm.contact'),
            ]);
            $this->modalTitle = 'Update Contact';
            try {
                $contact = $this->contactRepository->findOneBy('id', $params['id']);
                if ($contact instanceof EntityInterface) {
                    $this->form->setData($contact->getArrayCopy()); // sets the list_id in the form instance
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        // return our response which will render our form
        return new HtmlResponse(
            $this->renderer->render(
                'cm-partials::contact-modal',
                ['form' => $this->form, 'modalTitle' => $this->modalTitle]
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
                $this->form->setValidationGroup(['first_name', 'last_name', 'email', 'list_id']);
                if (! $this->form->isValid()) { // validates the form fields against the filter/validator chain that is attached to the form instance
                    $this->code = 422;
                    // really not needed but its an example of how to trigger a system message on some error condition
                    $this->htmxTrigger(['level' => 'warning', 'message' => 'Validation Failure.']);
                    $this->headers['HX-Success'] = 'false';
                    return new HtmlResponse(
                        $this->renderer->render(
                            'cm-partials::contact-modal',
                            ['form' => $this->form]
                        ),
                        $this->code, // important note, htmx is configured currently to swap all responses regardless of code
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
        $this->code = 202; //Accepted
        $this->modalTitle = 'Update Contact';
        try {
            $requestBody = $request->getParsedBody();
            $this->form->setAttributes([
                'hx-put' => $this->url->generate('cm.contact'), // set this on the form instance so it knows where to post
            ]);
            $this->form->setData($requestBody);
            $this->form->setValidationGroup(['id', 'list_id', 'first_name', 'last_name', 'email']);
            if (! $this->form->isValid()) {
                $this->code = 422;
                // really not needed but its an example of how to trigger a system message on some error condition
                $this->htmxTrigger(['level' => 'warning', 'message' => 'Validation Failure.']);
                $this->headers['HX-Success'] = 'false';
                return new HtmlResponse(
                    $this->renderer->render(
                        'cm-partials::update-contact',
                        ['form' => $this->form]
                    ),
                    $this->code, // important note, htmx is configured currently to swap all responses regardless of code
                    $this->headers
                );
            }

            $data    = $this->form->getData();
            $contact = $this->contactRepository->save($data, 'id');
            $this->htmxTrigger(['level' => 'success', 'message' => 'Successfully Updated.']);
            $this->headers[HtmxHeader::HX_Retarget->value] = '#contact_' . $contact->id;
            $this->headers[HtmxHeader::HX_Reswap->value] = 'outerHTML';
            $this->headers[HtmxHeader::HX_Reselect->value] = '#contact_' . $contact->id;
            $this->headers['HX-Success'] = 'true';
            return new HtmlResponse(
                $this->renderer->render(
                    'cm-partials::update-contact', // render the contact-modal, see the template files for the out-of-band swap
                    ['form' => $this->form, 'contact' => $contact, 'modalTitle' => $this->modalTitle]
                ),
                $this->code, // set the correct response code
                $this->headers
            );

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function handlePatch(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();
        $this->inputFilter->setData($requestBody);
        $this->inputFilter->setValidationGroup(['toList', 'fromList', 'id']);
        if ($this->inputFilter->isValid()) {
            $data = $this->inputFilter->getValues();
            $contact = $this->contactRepository->findOneBy('id', $data['id']);
            $contact->list_id = $data['toList'];
            $contact = $this->contactRepository->save($contact->getArrayCopy(), 'id');
            $this->htmxTrigger(['level' => 'success', 'message' => $contact->first_name . ' Contact Updated.']);
            $this->headers['HX-Success'] = 'true';
            $this->code = 202;
        } else {
            $this->code = 422;
            $this->htmxTrigger(['level' => 'danger', 'message' => 'Could not update contact.']);
            $this->headers['HX-Success'] = 'false';
        }
        return new HtmlResponse(
            $this->renderer->render(
                'cm-partials::update-contact', // render the contact-modal, see the template files for the out-of-band swap
                ['contact' => $contact, 'modalTitle' => $this->modalTitle]
            ),
            $this->code, // set the correct response code
            $this->headers
        );
    }

    // Handle contact deletion
    public function handleDelete(ServerRequestInterface $request): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        $params      = $routeResult->getMatchedParams();
        if (isset($params['id'])) {
            $contact = $this->contactRepository->findOneBy('id', $params['id']);
            try {
                $result = $this->contactRepository->delete(null, ['id' => $contact->id]);
                if ($result) {
                    $this->htmxTrigger(['level' => 'success', 'message' => 'Contact Deleted.']);
                    $this->headers['HX-Success'] = 'true';
                    $this->code = 200;
                    return new EmptyResponse(
                        $this->code,
                        $this->headers
                    );
                }
                $this->htmxTrigger(['level' => 'danger', 'message' => 'Could not delete contact!']);
                $this->headers['HX-Success'] = 'false';
                $this->code = 204;
            } catch (\Throwable $th) {
                $this->htmxTrigger(['level' => 'danger', 'message' => 'Internal Server Error. Could not delete contact!']);
                $this->headers['HX-Success'] = 'false';
                $this->code = 500;
            }
            return new EmptyResponse(
                $this->code,
                $this->headers
            );
        }
    }
}
