<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', ContactManager\Handler\LandingHandler::class, 'home'); // load custom layout for this handler
    $app->route(
        '/contacts/dashboard',
        [
            BodyParamsMiddleware::class,
            ContactManager\Handler\DashboardHandler::class
        ],
        [
            'GET',
            'POST',
        ],
        'contacts.dashboard'
    );
    $app->post(
        '/create/contact',
        ContactManager\Handler\CreateContactHandler::class,
        'contact.create.contact'
    );
    $app->post(
        '/create/list',
        [
            BodyParamsMiddleware::class,
            ContactManager\Handler\CreateListHandler::class,
        ],
        'contact.create.list'
    );
    $app->route(// This route acts as the login API endpoint
        '/login',
        [
            BodyParamsMiddleware::class,
            App\Handler\LoginHandler::class
        ],
        [
            'GET',
            'POST',
        ],
        'login'
    );
    $app->route(
        '/logout',
        [
            BodyParamsMiddleware::class,
            AuthenticationMiddleware::class,
            App\Handler\LogoutHandler::class
        ],
        [
            'GET',
        ],
        'logout'
    );
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
};
