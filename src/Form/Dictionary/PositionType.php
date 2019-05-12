<?php

namespace App\Form\Dictionary;

use App\Entity\Dictionary\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'position.fields.name'
            ])
            ->add('shortName', null, [
                'label' => 'position.fields.shortName'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Position::class,
        ]);
    }
}
