<?php

namespace App\Form;

use App\Entity\Footballer;
use App\Entity\GameTeamSquad;
use App\Repository\FootballerRepository;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTeamSquadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $home = $options['home_id'];
        $away = $options['away_id'];
        if($home == null)
        {
            $queryId = $away;
        }
        else if ($away == null)
        {
            $queryId = $home;
        }
        $builder
            ->add('isReserve')
            ->add('isCaptain')
            ->add('footballer' , EntityType::class, [
                'class' => Footballer::class,
                'choice_label' => 'surname',
                'query_builder' => function(FootballerRepository $repo) use ($queryId){
                    return $repo->createQueryBuilder('sm')
                        ->andWhere('sm.club = :param1')
                        ->setParameter('param1' , $queryId );
                },
                'attr' => array(
                    'class' => 'dropdown-with-footballers',
                ),
            ])
            ->add('number')

            //$builder->getData();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameTeamSquad::class,
            'home_id' => null,
            'away_id' => null,
        ]);
    }
}
