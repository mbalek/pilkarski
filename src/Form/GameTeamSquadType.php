<?php

namespace App\Form;

use App\Entity\GameTeamSquad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTeamSquadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isReserve')
            ->add('isCaptain')
            ->add('footballer')
            ->add('gameTeam')
            ->add('number')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameTeamSquad::class,
        ]);
    }
}
