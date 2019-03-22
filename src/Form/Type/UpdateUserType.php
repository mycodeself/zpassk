<?php

namespace App\Form\Type;

use App\Service\DTO\UpdateUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
            ])
            ->add('newPassword', PasswordType::class,[
                'required' => false,
                'attr' => ['id' => 'password'],
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('privateKey', HiddenType::class)
            ->add('publicKey', HiddenType::class)
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
            'data_class' => UpdateUserDTO::class,
            'attr' => ['id' => 'update_user_form']
        ]);
    }


}