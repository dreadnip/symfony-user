<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Message\User\ConfirmUser;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfirmController extends AbstractController
{
    /**
     * @Route("/confirm/{token}", name="confirm")
     */
    public function __invoke(
        string $token,
        SessionInterface $session,
        UserRepository $userRepository,
        TranslatorInterface $translator
    ): Response {
        $user = $userRepository->findOneBy(['confirmationToken' => $token]);

        if (!$user instanceof User) {
            $session->getBag('flashes')->add(
                'error',
                $translator->trans('Invalid confirmation token.')
            );

            return $this->redirectToRoute('login');
        }

        $this->dispatchMessage(new ConfirmUser($user));

        $session->getBag('flashes')->add(
            'success',
            $translator->trans('User confirmed.')
        );

        return $this->redirectToRoute('login');
    }
}
