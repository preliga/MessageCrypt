<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-09-30
 * Time: 19:10
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgotPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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