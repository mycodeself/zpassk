<?php


namespace App\Form\Type;


use App\Service\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
            ])
            ->add('email', EmailType::class)
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password'
            ])
            ->add('isAdmin', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new UserDTO(
                    $form->get('username')->getData() ?: '',
                    $form->get('email')->getData() ?: '',
                    $form->get('plainPassword')->getData() ?: '',
                    $form->get('isAdmin')->getData() ?: false
                );
            }
        ]);
    }


}