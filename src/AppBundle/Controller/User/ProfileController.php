<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 17:04
 */

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/user/profile/index", name="user_profile_index")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->doForm($user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Get Data
            $name = $form['name']->getData();
            $lastName = $form['lastName']->getData();
            $email = $form['email']->getData();
            $username = $form['username']->getData();
            $passwordFirst = $form['password']['first']->getData();
            $passwordSecond = $form['password']['second']->getData();
            $birthDate = $form['birthDate']->getData();

            $user = $this->getUser();
            $user->setName($name);
            $user->setLastname($lastName);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setBirthDate($birthDate);

            if(!empty($passwordFirst) && $passwordFirst == $passwordSecond)
            {
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $newPassword = $encoder->encodePassword($passwordFirst, $user->getSalt());

                $user->setPassword($newPassword);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash(
                'notice',
                'Save form'
            );
        }

        return $this->render('user/profile/index.html.twig',
            [
                'user' => $user,
                'form' => $form->createView()
            ]
        );
    }

    private function doForm($user)
    {
        $form = $this->createFormBuilder($user)
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
                    'required'   => true,
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
                    'required'   => true,
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
            ->getForm()
        ;

        return $form;
    }
}