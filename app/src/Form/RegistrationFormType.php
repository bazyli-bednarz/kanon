<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RegistrationFormType
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',
                EmailType::class,
                [
                    'label' => 'label.user_email',
                    'required' => true,
                    'attr' => ['max_length' => 150],
                ])
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'label.user_name',
                    'required' => true,
                    'attr' => ['max_length' => 150],
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new IsTrue(),
                ],
                'label' => 'label.agree_terms',
            ])
            ->add('password', PasswordType::class,
                [
                    'mapped' => true,
                    'required' => true,
                    'label' => 'label.user_password',
                    'attr' => ['autocomplete' => 'new-password', 'min-length' => 6, 'max_length' => 4096],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
