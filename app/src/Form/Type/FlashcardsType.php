<?php

namespace App\Form\Type;

use App\Entity\Canon;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FlashcardsType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'canons',
                EntityType::class,
                [
                    'mapped' => false,
                    'class' => Canon::class,
                    'choice_label' => function ($canon): string {
                        return $canon->getName();
                    },
//                    'choice_filter' => ChoiceList::filter(
//                        $this,
//                        function ($canon) {
//                            $user = $this->security->getUser();
//                            return ($canon->getVisibility()) || ($canon->getAuthor() === $user);
//                        },
//                        'canon'
//                    ),
                    'attr' => [
                        'class' => 'selectize-multi',
                    ],
                    'label' => 'label.piece_canon',
                    'placeholder' => 'label.none',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
        ;
    }

}