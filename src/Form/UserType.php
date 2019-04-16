<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'user.fields.username'
            ])
            ->add('email' ,null, [
                'label' => 'user.fields.email'
            ])
            ->add('plainPassword' , PasswordType::class , [
                'label' => 'user.fields.password'
            ])
            ->add('isAdmin' , CheckboxType::class, [
                'mapped' => false,
                'label' => 'user.fields.isAdmin',
                'required' => false,
            ])
            ->add('firstName' , null , [
                'label' => 'user.fields.firstName'
            ])
            ->add('lastName', null, [
                'label' => 'user.fields.lastName'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
