<?php

namespace App\Form\Type;

use App\Entity\Canon;
use App\Entity\Composer;
use App\Entity\Piece;
use App\Entity\Scale;
use App\Form\DataTransformer\TagsDataTransformer;
use App\Form\DataTransformer\YouTubeLinkDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Scale type.
 */

class PieceType extends AbstractType
{
    private TranslatorInterface $translator;

    private TagsDataTransformer $tagsDataTransformer;

    private YouTubeLinkDataTransformer $youTubeLinkDataTransformer;

    private Security $security;

    public function __construct(TranslatorInterface $translator, TagsDataTransformer $tagsDataTransformer, YouTubeLinkDataTransformer $youTubeLinkDataTransformer, Security $security)
    {
        $this->translator = $translator;
        $this->tagsDataTransformer = $tagsDataTransformer;
        $this->youTubeLinkDataTransformer = $youTubeLinkDataTransformer;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'label.piece_name',
                    'required' => true,
                    'attr' => ['max_length' => 150],
                ])
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label.piece_description',
                    'required' => false,
                    'attr' => ['max_length' => 500],
                ])
            ->add(
                'year',
                NumberType::class,
                [
                    'label' => 'label.piece_year',
                    'required' => false,
                ])
            ->add(
                'link',
                TextType::class,
                [
                    'label' => 'label.piece_link',
                    'required' => true,
                    'attr' => ['max_length' => 500],
                ])
            ->add(
                'composer',
                EntityType::class,
                [
                    'class' => Composer::class,
                    'choice_label' => function ($composer) {
                        return $composer->getName() . ' ' . $composer->getLastName();
                    },
                    'attr' => [
                        'class' => 'selectize',
                    ],
                    'label' => 'label.piece_composer',
                    'placeholder' => 'label.none',
                    'required' => true,
                ]
            )
            ->add(
                'scale',
                EntityType::class,
                [
                    'class' => Scale::class,
                    'choice_label' => function ($scale): string {
                        return $this->translator->trans($scale->getName());
                    },
                    'attr' => [
                        'class' => 'selectize',
                    ],
                    'label' => 'label.piece_scale',
                    'placeholder' => 'label.none',
                    'required' => true,
                ]
            )
            ->add(
                'canons',
                EntityType::class,
                [
                    'mapped' => false,
                    'class' => Canon::class,
                    'choice_label' => function ($canon): string {
                        return $canon->getName();
                    },
                    'choice_filter' => ChoiceList::filter(
                        $this,
                        function ($canon) {
                            $userFriends = $canon->getAuthor()->getFriends();
                            $user = $this->security->getUser();


                            return ($userFriends->contains($user)) || ($canon->getAuthor() === $user);
                        },
                        'canon'
                    ),
                    'attr' => [
                        'class' => 'selectize-multi',
                    ],
                    'label' => 'label.piece_canon',
                    'placeholder' => 'label.none',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'tags',
                TextType::class,
                [
                    'label' => 'label.tags',
                    'required' => false,
                    'attr' => ['max_length' => 200],
                ]
            )

            ->get('tags')->addModelTransformer(
                $this->tagsDataTransformer
            )
        ;

        $builder->get('link')->addModelTransformer(
            $this->youTubeLinkDataTransformer
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'piece';
    }
}
