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
     * @Route("/user/profile/myprofile", name="user_profile_myprofile")
     */
    public function myprofileAction(Request $request)
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

        return $this->render('user/profile/myprofile.html.twig',
            [
                'user' => $user,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/user/profile/profile/{id}", name="user_profile_profile")
     */
    public function profileAction($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id)
        ;

        $friend1 = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['userid1' => $this->getUser(), 'userid2' => $user])
        ;

        $friend2 = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['userid1' => $user, 'userid2' => $this->getUser()])
        ;


        $form = $this->createForm(ProfileType::class, $user); //$this->doForm($user);
        $form->handleRequest($request);


        return $this->render('user/profile/profile.html.twig',
            [
                'ownUser' => $this->getUser(),
                'user' => $user,
                'friend1' => $friend1,
                'friend2' => $friend2
            ]
        );
    }
}