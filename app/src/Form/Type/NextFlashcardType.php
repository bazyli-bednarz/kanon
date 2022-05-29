<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Flashcards type.
 */
class NextFlashcardType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'draw', SubmitType::class,
                [
                    'label' => 'label.draw',
                    'attr' => [
                        'class' => 'w-100 btn btn-primary',
                    ],
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'flashcards';
    }
}
