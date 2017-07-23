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
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        die(var_dump($options));
//        die(var_dump($this->configureOptions()->getParameter('brochures_directory')));
        
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Name'
                        ]
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Last name'
                        ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'attr' =>
                        [
                            'class' => 'form-control',
                            'placeholder' => 'E-mail'
                        ]
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'attr' =>
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Username'
                        ]
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'required' => false,
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
                'birthDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' =>
                        [
                            'class' => 'js-datepicker'
                        ]
                ]
            )
            ->add(
                'brochure',
                FileType::class,
                [
                    'required' => false,
                    'label' => "Brochure (PDF file)",
                    'data_class' => null,
                    'attr' =>
                        [
//                            'class' => 'form-control',
                        ]
//                    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
//                    'property_path' => '/uploads/brochures/'
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

//        $builder->get('brochure')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($brochure) {
//                    $file = null;
//                    $path = "uploads/brochures/$brochure";
//                    $file = file($path);
////                    die(var_dump(1, $brochure, $path, $file));
//                    return $file;
//                    // transform the array to a string
////                    return implode(', ', $tagsAsArray);
//                },
//                function ($tagsAsString) {
//                    die(var_dump(2, $tagsAsString));
//                    // transform the string back to an array
//                    return explode(', ', $tagsAsString);
//                }
//            ))
//        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}