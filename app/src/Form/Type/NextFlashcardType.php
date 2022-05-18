<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Scale type.
 */
class NextFlashcardType extends AbstractType
{
    private TranslatorInterface $translator;


    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'draw', SubmitType::class,
                [
                    'label' => 'label.draw',
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'flashcards';
    }
}
