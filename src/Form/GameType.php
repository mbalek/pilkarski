<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gameDateTime', DateTimeType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
                'attr' => ['class' => 'js-datepicker']
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Deactivated' => 0,
                    'Finished' => 1,
                    'Live' => 2,
                    'Upcoming' => 3
                ],
            ])
            ->add('round')
            ->add('description')

            ->add('homeTeam', GameTeamType::class)
            ->add('awayTeam', GameTeamType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
