<?php

namespace App\Form;

use App\Entity\GameTeam;
use App\Entity\GameTeamSquad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $home = $options['home_id'];
        $away = $options['away_id'];
        $builder
            ->add('club')
            ->add('formation')
            ->add('gameTeamSquads', CollectionType::class, [
                'entry_type' => GameTeamSquadType::class,
                'entry_options' => ['label' => false,'home_id' => $home, 'away_id' => $away],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameTeam::class,
            'home_id' => null,
            'away_id' => null,
        ]);
    }
}
