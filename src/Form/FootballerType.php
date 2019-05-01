<?php

namespace App\Form;

use App\Entity\Footballer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FootballerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name' , null , [
                'label' => 'footballer.fields.name',
            ])
            ->add('surname', null , [
                'label' => 'footballer.fields.surname',
            ])
            ->add('birthdate', null , [
                'label' => 'footballer.fields.birthdate',
            ])
            ->add('goals', null , [
                'label' => 'footballer.fields.goal',
            ])
            ->add('assists', null , [
                'label' => 'footballer.fields.assists',
            ])
            ->add('yellowCards', null , [
                'label' => 'footballer.fields.yellowCards',
            ])
            ->add('redCards', null , [
                'label' => 'footballer.fields.redCards',
            ])
            ->add('position', null , [
                'label' => 'position.display.position',
            ])
            ->add('imageFile',VichImageType::class, [
                'attr' => ['class' => 'btn btn-default pull-right'],
                'label' => 'footballer.fields.image',
                'required' => false,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Footballer::class,
        ]);
    }
}
