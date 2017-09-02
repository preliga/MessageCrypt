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
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/user/profile/uploadAvatar/", name="user_profile_uploadAvatar")
     */
    public function uploadAvatarAction(Request $request)
    {
        $avatarBase64 = $request->request->get('avatar');
//        $avatarImage = base64_encode($request->request->get('avatar'));
//        file_put_contents("test.jpg", $avatarImage);
//        $x = $this->save_base64_image($avatarBase64, 'test');
//        die(var_dump($x));

        
//        $avatar->move(
//            $this->getParameter('avatars_directory'),
//            $avatarName
//        );

//        die(var_dump($this->getParameter('avatars_directory')));

        $avatarsDirectory = $this->getParameter('avatars_directory');
//        die(var_dump($avatarsDirectory));
        $splited = explode(',', substr( $avatarBase64 , 5 ) , 2);
        $mime = $splited[0];
        $data = $splited[1];

        $mime_split_without_base64 = explode(';', $mime,2);
        $mime_split = explode('/', $mime_split_without_base64[0],2);

        if( count( $mime_split ) == 2)
        {
            $extension = $mime_split[1];
            if($extension == 'jpeg'){
                $extension = 'jpg';
            }

            $avatarName = md5(uniqid()) . '.' . $extension;
            file_put_contents( $avatarsDirectory . '/' . $avatarName, base64_decode($data) );

            $user = $this->getUser();

            $avatarOld = $user->getAvatar();

            if(file_exists($avatarsDirectory. '/' .$avatarOld) ) {
                unlink($avatarsDirectory. '/' .$avatarOld);
            }


            $user->setAvatar($avatarName);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return new Response(json_encode(array("success" => true)));
        }
        else {
            return new Response(json_encode(array("message" => 'Bad format image.', "error" => true)));
        }
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