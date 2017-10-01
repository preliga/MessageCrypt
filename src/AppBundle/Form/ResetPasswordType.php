<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-23
 * Time: 15:09
 */

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'password',
                RepeatedType::class,
                [
                    'required' => true,
                    'type' => PasswordType::class,
                    'invalid_message' => "The password fields must match.",
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                    'options' => [
                        'attr' => [
                            'class' => 'password-field'
                        ],
                    ],
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' =>
                        [
                            'class' => 'btn btn-primary',
                            'style' => 'margin-bottom:15px; width: 300px; height: 50px'
                        ]
                ]
            )
        ;
    }
}