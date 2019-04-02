<?php

namespace App\Form\Type;

use App\Service\DTO\PasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => ''
            ])
            ->add('username', TextType::class, [
                'empty_data' => '',
            ])
            ->add('password', PasswordType::class, [
                'empty_data' => '',
            ])
            ->add('url', TextType::class, [
                'empty_data' => '',
            ])
            ->add('key', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PasswordDTO::class,
            'attr' => [
                'id' => 'password_form'
            ]
        ]);
    }


}