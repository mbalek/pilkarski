<?php

namespace App\Form;

use App\Entity\GameTeam;
use App\Repository\ClubRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTeamTypeSimple extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('club', null, [
                'label' => 'club.display.selClub'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameTeam::class,
        ]);
    }
}
