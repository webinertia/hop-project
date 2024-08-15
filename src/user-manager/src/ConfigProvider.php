<?php

declare(strict_types=1);

namespace UserManager;

use Fig\Http\Message\RequestMethodInterface as HttpMethod;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'authentication' => $this->getAuthenticationConfig(),
            'dependencies'   => $this->getDependencies(),
            'routes'         => $this->getRouteConfig(),
            'templates'      => $this->getTemplates(),
        ];
    }

    public function getAuthenticationConfig(): array
    {
        return [
            //'redirect' => '/user/account', // todo: decide on best way to handle this..
            'username' => 'email',
            'password' => 'password',
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => UserRepository\TableGateway::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories'  => [
                Handler\LoginHandler::class          => Handler\LoginHandlerFactory::class,
                Handler\LogoutHandler::class         => Handler\LogoutHandlerFactory::class,
                Handler\RegistrationHandler::class   => Handler\RegistrationHandlerFactory::class,
                Handler\ResetPasswordHandler::class  => Handler\ResetPasswordHandlerFactory::class,
                Handler\VerifyAccountHandler::class  => Handler\VerifyAccountHandlerFactory::class,
                Middleware\IdentityMiddleware::class => Middleware\IdentityMiddlewareFactory::class,
                UserRepository\TableGateway::class   => UserRepository\TableGatewayFactory::class,
            ],
        ];
    }

    public function getRouteConfig(): array
    {
        return [
            [
                'path'       => '/login',
                'name'       => 'login',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    Handler\LoginHandler::class,
                ],
                'allowed_methods' => [HttpMethod::METHOD_GET, HttpMethod::METHOD_POST]
            ],
            [
                'path'       => '/logout',
                'name'       => 'logout',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    AuthenticationMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
            ],
        ];
    }

    public function getTemplates(): array
    {
        return [
            'paths' => [
                'user-manager' => [__DIR__ . '/../templates/user-manager'],
                'user-manager-partials' => [__DIR__ . '/../templates/partials'],
            ],
        ];
    }
}
