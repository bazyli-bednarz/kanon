<?php

namespace App\Form\Type;

use App\Entity\Canon;
use App\Entity\Composer;
use App\Entity\Piece;
use App\Entity\Scale;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Canon type.
 */

class CanonType extends AbstractType
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
                    'label' => 'label.canon_name',
                    'required' => true,
                    'attr' => ['max_length' => 150],
                ])
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label.canon_description',
                    'required' => false,
                    'attr' => ['max_length' => 255],
                ])

            ->add(
                'pieces',
                EntityType::class,
                [
                    'class' => Piece::class,
                    'choice_label' => function ($piece): string {
                        return $piece->getName();
                    },
                    'attr' => [
                        'class' => 'selectize-multi',
                    ],
                    'label' => 'label.canon_piece',
                    'placeholder' => 'label.none',
                    'required' => false,
                    'by_reference' => false,
                    'expanded' => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'visibility',
                CheckboxType::class,
                [
                    'label' => 'label.canon_visibility',
                    'required' => false,
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Canon::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'canon';
    }
}
