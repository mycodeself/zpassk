<?php

namespace App\Form\Type;

use App\Service\DTO\ChangePasswordWithTokenDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('token', HiddenType::class)
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'The password fields must match.',
            ])
        ;

        // hack to avoid returning null when no values was send
        $builder->get('newPassword')
            ->addModelTransformer(new CallbackTransformer(
                function ($array) {
                    return (empty($array)) ? '' : $array[0];
                },
                function ($string) {
                    return is_null($string) ? '' : $string;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordWithTokenDTO::class,
        ]);
    }


}