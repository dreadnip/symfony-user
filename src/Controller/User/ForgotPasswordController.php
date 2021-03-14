<?php

namespace App\Controller\User;

use App\Form\User\ForgotPasswordType;
use App\Message\User\SendPasswordReset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function __invoke(
        Request $request,
        SessionInterface $session
    ): Response {
        $form = $this->createForm(ForgotPasswordType::class, new SendPasswordReset());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatchMessage($form->getData());

            $session->getBag('flashes')->add('success', 'user.registered');

            return $this->redirectToRoute('login');
        }

        return $this->render('user/forgot.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
