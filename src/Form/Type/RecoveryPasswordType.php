<?php

namespace App\Form\Type;

use App\Service\DTO\RecoveryPasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecoveryPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecoveryPasswordDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new RecoveryPasswordDTO(
                    $form->get('email')->getData() ?: ''
                );
            }
        ]);
    }


}