<?php

namespace App\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
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
    private UserRepository $userRepository;

    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router,
        UserRepository $userRepository
    ) {
        $this->translator = $translator;
        $this->router = $router;
        $this->userRepository  = $userRepository;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isConfirmed()) {
            throw new UnconfirmedAccountException(
                $this->translator->trans(
                    'You have not confirmed your email address. <a href="%requestConfirmationUrl%">Resend the confirmation mail</a>',
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

        if (!$user->isEnabled()) {
            throw new DisabledException('This account has been disabled.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getPasswordResetToken() !== null) {
            $user->erasePasswordResetRequest();

            $this->userRepository->save();
        }
    }
}
