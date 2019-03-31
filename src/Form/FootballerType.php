<?php

namespace App\Form;

use App\Entity\Footballer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FootballerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('birthdate')
            ->add('goals')
            ->add('assists')
            ->add('yellowCards')
            ->add('redCards')
            ->add('position')
            ->add('club')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Footballer::class,
        ]);
    }
}
