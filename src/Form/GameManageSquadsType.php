<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameManageSquadsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gameDateTime', DateTimeType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
                'label' => 'gameManageSquads.fields.gameDateTime'
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'gameManageSquads.fields.deactivated' => 0,
                    'gameManageSquads.fields.finished' => 1,
                    'gameManageSquads.fields.live' => 2,
                    'gameManageSquads.fields.upcoming' => 3
                ],
                'label' => 'gameManageSquads.fields.status'
            ])
            ->add('round')
            ->add('description' , null, [
                'label' => 'gameManageSquads.fields.description'
            ])

            ->add('homeTeam', GameTeamType::class,[
                'label' => 'gameManageSquads.fields.homeTeam'
            ])
            ->add('awayTeam', GameTeamType::class, [
                'label' => 'gameManageSquads.fields.awayTeam'
            ])
            ->add('save', SubmitType::class, ['label' => 'gameManageSquads.fields.updateSquads'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
