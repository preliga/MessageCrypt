<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        if(!empty($error))
        {
            $this->addFlash(
                'error',
                $error->getMessage()
            );
        }
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('home/login.html.twig',
            [
                'last_username' => $lastUsername,
            ]
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction(Request $request)
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User;//$this->getUser();
        $password = $user->getPassword();
        $brochureName = $user->getBrochure();
        $avatarName = $user->getAvatar();

        $form = $this->createForm(RegistryType::class, $user); //$this->doForm($user);
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
//                $brochure = $user->getBrochure();
//                if(!empty($brochure)) {
//                    $brochureName = md5(uniqid()) . '.' . $brochure->guessExtension();
//                    $brochure->move(
//                        $this->getParameter('brochures_directory'),
//                        $brochureName
//                    );
//
//                }
//                $user->setBrochure($brochureName);
                /////

                // avatar
//                $avatar = $user->getAvatar();
//                if(!empty($avatar)) {
//                    $avatarName = md5(uniqid()) . '.' . $avatar->guessExtension();
//                    $avatar->move(
//                        $this->getParameter('avatars_directory'),
//                        $avatarName
//                    );
//
//                }
//                $user->setAvatar($avatarName);
                /////
                ///

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Save register form'
                );

                return $this->redirectToRoute('login');
            }
            else
            {
                $this->addFlash(
                    'error',
                    'Form is not valid'
                );
            }
        }

        return $this->render('home/register.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
