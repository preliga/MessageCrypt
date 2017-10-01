<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistryType;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends Controller
{

    public function preAction(FilterControllerEvent $event)
    {
        $user = $this->getUser();
        if(!empty($user)){
            $url = $this->generateUrl('user_home_index');
            $event->setController(function() use ($url) {
                return new RedirectResponse($url);
            });
        }
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        if (!empty($error)) {
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
        $user = new User;
        $password = $user->getPassword();

        $form = $this->createForm(RegistryType::class, $user); //$this->doForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                // password
                $passwordFirst = $form['password']['first']->getData();
                $passwordSecond = $form['password']['second']->getData();

                if (!empty($passwordFirst) && $passwordFirst == $passwordSecond) {
                    $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                    $password = $encoder->encodePassword($passwordFirst, $user->getSalt());
                }
                $user->setPassword($password);
                /////


                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Save register form'
                );

                return $this->redirectToRoute('login');
            } else {
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

    /**
     * @Route("/forgotPassword", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $email = $form['email']->getData();

                $user = $this->getDoctrine()->getManager()
                    ->getRepository(User::class)
                    ->findOneBy(['email' => $email]);

                if (!empty($user)) {

                    $token = sha1(uniqid());

                    $url = $this->generateUrl('reset_password', ['token' => $token], 0);

                    $user->setToken($token);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $message = (new \Swift_Message('Forgot password?'))
                        ->setFrom('send@example.com')
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                                'emails/forgotPassword.html.twig',
                                [
                                    'user' => $user,
                                    'url' => $url
                                ]
                            ),
                            'text/html'
                        );

                    $mailer->send($message);


                    $this->addFlash(
                        'notice',
                        'Mail was send'
                    );
                } else {
                    $this->addFlash(
                        'error',
                        'Mail is incorrect'
                    );
                }
            } else {
                $this->addFlash(
                    'error',
                    'Form is not valid'
                );
            }
        }

        return $this->render('home/forgotPassword.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/resetPassword/{token}", name="reset_password")
     */
    public function resetPasswordAction($token, Request $request)
    {
        $user = $this->getDoctrine()->getManager()
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);

        if (empty($user)) {
            return $this->redirectToRoute('login');
        }


        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {


                $passwordFirst = $form['password']['first']->getData();
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $password = $encoder->encodePassword($passwordFirst, $user->getSalt());

                $user->setPassword($password);

                $user->setToken(null);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Password was change'
                );

                return $this->redirectToRoute('login');

            } else {
                $this->addFlash(
                    'error',
                    'Form is not valid'
                );
            }
        }


        return $this->render('home/resetPassword.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
