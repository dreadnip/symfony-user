<?php

declare(strict_types=1);

use App\Entity\User\User;
use App\Security\CustomAuthenticator;
use App\Security\UserChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'enable_authenticator_manager' => true,
        'encoders' => [
            User::class => [
                'algorithm' => 'auto',
            ],
        ],
        'providers' => [
            'app_user_provider' => [
                'entity' => [
                    'class' => User::class,
                    'property' => 'email',
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'lazy' => true,
                'provider' => 'app_user_provider',
                'custom_authenticator' => CustomAuthenticator::class,
                'user_checker' => UserChecker::class,
                'logout' => ['path' => 'logout'],
                'remember_me' => [
                    'secret' => '%kernel.secret%',
                    'lifetime' => 604800,
                    'path' => '/',
                    'always_remember_me' => true,
                ],
            ],
        ],
        'access_control' => [
            ['path' => '^/admin', 'roles' => 'ROLE_ADMIN'],
            ['path' => '^/profile', 'roles' => 'ROLE_USER'],
            ['path' => '^/login', 'roles' => 'PUBLIC_ACCESS'],
            ['path' => '^/resend-confirmation', 'roles' => 'PUBLIC_ACCESS'],
            ['path' => '^/register', 'roles' => 'PUBLIC_ACCESS'],
            ['path' => '^/confirm', 'roles' => 'PUBLIC_ACCESS'],
            ['path' => '^/password-reset', 'roles' => 'PUBLIC_ACCESS'],
        ],
    ]);
};
