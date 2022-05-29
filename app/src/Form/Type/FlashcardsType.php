<?php

namespace App\Form\Type;

use App\Entity\Canon;
use App\Entity\User;
use PhpParser\Node\Stmt\Return_;
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
                    'preferred_choices' => function($canon) {
                        $userFriends = $canon->getAuthor()->getFriends();
                        $user = $this->security->getUser();

                        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                            return ($userFriends->contains($user)) || ($canon->getAuthor() === $user);
                        }
                        return true;
                    },
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
