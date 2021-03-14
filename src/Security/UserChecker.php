<?php

namespace App\Security;

use App\Entity\User\User;
use App\Security\Exception\UnconfirmedAccountException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserChecker implements UserCheckerInterface
{
    private TranslatorInterface $translator;
    private RouterInterface $router;

    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isEnabled()) {
            throw new DisabledException('Bad credentials.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isConfirmed()) {
            throw new UnconfirmedAccountException(
                $this->translator->trans(
                    'You have not confirmed your e-mail address. <a href="%requestConfirmationUrl%">Resend the confirmation mail</a>',
                    [
                        '%requestConfirmationUrl%' => $this->router->generate(
                            'resend_confirmation',
                            [
                                'token' => $user->getConfirmationToken(),
                            ]
                        ),
                    ]
                )
            );
        }
    }
}
