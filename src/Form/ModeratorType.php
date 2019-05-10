<?php
/**
 * Created by PhpStorm.
 * User: Ikki
 * Date: 10.05.2019
 * Time: 11:22
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class ModeratorType extends AbstractType
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
            ->add('firstName' , null , [
                'label' => 'user.fields.firstName'
            ])
            ->add('lastName', null, [
                'label' => 'user.fields.lastName'
            ])
            ->add('moderatingLeague' , null , [
                'label' => 'user.fields.moderatingLeague'
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