<?php

namespace App\Form;

use App\Entity\Footballer;
use App\Entity\GameTeamSquad;
use App\Repository\FootballerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('footballer' , EntityType::class, [
                'class' => Footballer::class,
                'choice_label' => 'surname',
                'query_builder' => function(FootballerRepository $repo) {
                    return $repo->createQueryBuilder('u')
                        ->orderBy('u.surname', 'ASC');
                },
                'attr' => array(
                    'class' => 'dropdown-with-footballers',
                ),
            ])
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
