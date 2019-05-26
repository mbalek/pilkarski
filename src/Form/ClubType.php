<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'club.fields.name',
            ])
            ->add('city', null, [
                'label' => 'club.fields.city',
            ])
            ->add('stadium', null, [
                'label' => 'club.fields.stadium',
            ])
            ->add('imageFile',VichImageType::class, [
                'attr' => ['class' => 'btn btn-default pull-right'],
                'label' => 'Logo',
                'required' => false,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
