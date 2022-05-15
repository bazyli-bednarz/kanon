<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProfileImageType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image',
            ChoiceType::class,
                [
                    'choices' => $this->determineChoices(),
                    'expanded' => true,
                    'mapped' => false,
                    'label' => 'label.profile_image',
                    'required' => true,
                ]
            )
        ;
    }

    public function determineChoices(): array {
        $level = $this->security->getUser()->getLevel();
        $choices = [1,2,3,4,5];
        switch ($level) {
            case 1:
                return $choices;
            case 2:
                return array_merge($choices, [6,7,8]);
            case 3:
                return array_merge($choices, [6,7,8,9,10]);
            case 4:
                return array_merge($choices, [6,7,8,9,10,11,12]);
            case 5:
                return array_merge($choices, [6,7,8,9,10,11,12,13,14]);
            case 6:
                return array_merge($choices, [6,7,8,9,10,11,12,13,14,15,16]);
            case 7:
                return array_merge($choices, [6,7,8,9,10,11,12,13,14,15,16,17]);
            case 8:
                return array_merge($choices, [6,7,8,9,10,11,12,13,14,15,16,17,18]);
            case 9:
                return array_merge($choices, [6,7,8,9,10,11,12,13,14,15,16,17,18,19]);
            default:
                return array_merge($choices, [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
