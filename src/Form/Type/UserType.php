<?php


namespace App\Form\Type;


use App\Service\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('plainPassword', RepeatedType::class, [
                'first_options' => [
                  'label' => 'Password'
                ],
                'second_options' => [
                    'label' => 'Repeat password'
                ],
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'The password fields must match.',
            ])
            ->add('isAdmin', CheckboxType::class, [
                'required' => false,
            ])
            ->add('privateKey', HiddenType::class)
            ->add('publicKey', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'attr' => ['id' => 'user_form'],
            'empty_data' => function (FormInterface $form) {
                return new UserDTO(
                    $form->get('username')->getData() ?: '',
                    $form->get('email')->getData() ?: '',
                    $form->get('plainPassword')->getData() ?: '',
                    $form->get('isAdmin')->getData() ?: false,
                    $form->get('publicKey')->getData() ?: '',
                    $form->get('privateKey')->getData() ?: ''
                );
            }
        ]);
    }


}