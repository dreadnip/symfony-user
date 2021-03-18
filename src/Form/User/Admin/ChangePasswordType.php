<?php

namespace App\Form\User\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'password',
                PasswordType::class,
                [
                    'password_confirm' => true,
                    'password_options' => [
                        'label' => 'Password',
                        'constraints' => new NotBlank(),
                    ],
                    'password_confirm_options' => [
                        'label' => 'Confirm password',
                    ],
                ]
            )
            ->add(
                'current',
                PasswordType::class,
                [
                    'label' => 'Current password',
                    'password_options' => [
                        'constraints' => new UserPassword(),
                    ],
                    'mapped' => false,
                ]
            )
        ;
    }
}
