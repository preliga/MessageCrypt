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
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;

class ProfileController extends Controller
{
    /**
     * @Route("/user/profile/index", name="user_profile_index")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $password = $user->getPassword();
        $brochureName = $user->getBrochure();
        $avatarName = $user->getAvatar();

        $form = $this->createForm(ProfileType::class, $user); //$this->doForm($user);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if( $form->isValid()) {
                // password
                $passwordFirst = $form['password']['first']->getData();
                $passwordSecond = $form['password']['second']->getData();

                if (!empty($passwordFirst) && $passwordFirst == $passwordSecond) {
                    $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                    $password = $encoder->encodePassword($passwordFirst, $user->getSalt());
                }
                $user->setPassword($password);
                /////

                // brochure
                $brochure = $user->getBrochure();
                if(!empty($brochure)) {
                    $brochureName = md5(uniqid()) . '.' . $brochure->guessExtension();
                    $brochure->move(
                        $this->getParameter('brochures_directory'),
                        $brochureName
                    );

                }
                $user->setBrochure($brochureName);
                /////

                // avatar
                $avatar = $user->getAvatar();
                if(!empty($avatar)) {
                    $avatarName = md5(uniqid()) . '.' . $avatar->guessExtension();
                    $avatar->move(
                        $this->getParameter('avatars_directory'),
                        $avatarName
                    );

                }
                $user->setAvatar($avatarName);
                /////
                ///
                ///
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Save form'
                );
            }
            else
            {
                $this->addFlash(
                    'error',
                    'Form is not valid'
                );
            }
        }

        return $this->render('user/profile/index.html.twig',
            [
                'user' => $user,
                'form' => $form->createView()
            ]
        );
    }

//    private function doForm($user)
//    {
//        $form = $this->createFormBuilder($user)
//            ->add(
//                'name',
//                TextType::class,
//                [
//                    'attr' =>
//                        [
//                            'class' => 'form-control',
//                            'placeholder' => 'Name'
//                        ]
//                ]
//            )
//            ->add(
//                'lastName',
//                TextType::class,
//                [
//                    'attr' =>
//                        [
//                            'class' => 'form-control',
//                            'placeholder' => 'Last name'
//                        ]
//                ]
//            )
//            ->add(
//                'email',
//                EmailType::class,
//                [
//                    'required'   => true,
//                    'attr' =>
//                        [
//                            'class' => 'form-control',
//                            'placeholder' => 'E-mail'
//                        ]
//                ]
//            )
//            ->add(
//                'username',
//                TextType::class,
//                [
//                    'required'   => true,
//                    'attr' =>
//                        [
//                            'class' => 'form-control',
//                            'placeholder' => 'Username'
//                        ]
//                ]
//            )
//            ->add(
//                'password',
//                RepeatedType::class,
//                [
//                    'required'   => false,
//                    'type' => PasswordType::class,
//                    'invalid_message' => "The password fields must match.",
//                    'first_options'  => ['label' => 'Password'],
//                    'second_options' => ['label' => 'Repeat Password'],
//                    'options' => [
//                        'attr' => [
//                            'class' => 'password-field'
//                        ],
//                    ],
//                ]
//            )
//            ->add(
//                'birthDate',
//                DateType::class,
//                [
//                    'widget' => 'single_text',
//                    'html5' => false,
//                    'attr' =>
//                        [
//                            'class' => 'js-datepicker'
//                        ]
//                ]
//            )
//            ->add(
//                'brochure',
//                FileType::class,
//                [
//                    'label' => 'Brochure (PDF file)',
////                    'widget' => 'single_text',
////                    'html5' => false,
////                    'attr' =>
////                        [
////                            'class' => 'js-datepicker'
////                        ]
//                ]
//            )
//            ->add(
//                'save',
//                SubmitType::class,
//                [
//                    'label' => 'Save',
//                    'attr' =>
//                        [
//                            'class' => 'btn btn-primary',
//                            'style' => 'margin-bottom:15px; width: 300px; height: 50px'
//                        ]
//                ]
//            )
//            ->getForm()
//        ;
//
//        return $form;
//    }
}