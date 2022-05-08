<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'label.user_name',
                    'required' => true,
                    'attr' => ['max_length' => 150],
                ])
            ->add('oldPassword', PasswordType::class,
                [
                    'mapped' => false,
                    'required' => true,
                    'label' => 'label.user_old_password',
                    'attr' => ['min-length' => 6, 'max_length' => 4096],
                ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'label' => 'label.new_password',

                'first_options' => [
                    'attr' => ['autocomplete' => 'on', 'min-length' => 6, 'max_length' => 4096],
                    'label' => 'label.new_password',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'label.password_repeat',
                ],
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
