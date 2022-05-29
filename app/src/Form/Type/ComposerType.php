<?php

/**
 * Composer type.
 */

namespace App\Form\Type;

use App\Entity\Composer;
use App\Entity\Period;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ComposerType extends AbstractType
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
                'name',
                TextType::class,
                [
                    'label' => 'label.composer_name',
                    'required' => true,
                    'attr' => ['max_length' => 150],
                ])
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'label.composer_last_name',
                    'required' => false,
                    'attr' => ['max_length' => 150],
                ])
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label.composer_description',
                    'required' => false,
                    'attr' => ['max_length' => 500],
                ])
            ->add(
                'birthYear',
                NumberType::class,
                [
                    'label' => 'label.composer_birth_year',
                    'required' => false,
                ])
            ->add(
                'deathYear',
                NumberType::class,
                [
                    'label' => 'label.composer_death_year',
                    'required' => false,
                ])
            ->add(
                'period',
                EntityType::class,
                [
                    'class' => Period::class,
                    'choice_label' => function ($period) {
                        return $this->translator->trans($period->getName());
                    },
                    'attr' => [
                        'class' => 'selectize',
                    ],
                    'label' => 'label.period_name',
                    'placeholder' => 'label.none',
                    'required' => true,
                ]
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Composer::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'composer';
    }
}
