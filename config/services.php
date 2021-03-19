<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $parameters = $container->parameters();
    $parameters->set('mailer.default_sender_name', 'Demo');
    $parameters->set('mailer.default_sender_email', 'foo@bar.com');

    $services = $container->services()->defaults()
        ->private()
        ->autoconfigure()
        ->autowire()
        ->bind('$fromName', 'fsd')
        ->bind('$fromMail', 'mfsdf');

    $services
        ->load('App\\', __DIR__ . '/../src/*')
        ->exclude('../src/{Entity,Tests,Kernel.php}');

    $services
        ->load('App\\Controller\\', __DIR__ . '/../src/Controller')
        ->tag('controller.service_arguments');
};
