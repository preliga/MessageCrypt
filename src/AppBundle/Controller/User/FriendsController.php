<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 17:04
 */

namespace AppBundle\Controller\User;

use AppBundle\Entity\User;
use AppBundle\Entity\Friend;
//use AppBundle\Entity\Frienddictionary;
use AppBundle\Table\FriendsSearchTableType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FriendsController extends Controller
{
    /**
     * @Route("/user/friends/myfriends", name="user_friends_myfriends")
     */
    public function myfriendsAction(Request $request)
    {
        $friends1 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['userid1' =>  $this->getUser()])
        ;
        $friends2 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['userid2' =>  $this->getUser()])
        ;

//        $query = $this->getDoctrine()
//            ->getRepository('AppBundle:Friend')
//            ->createQueryBuilder('f')
//            ->where('f.userid1 = :user1')
//            ->setParameter('user1', $this->getUser() )
//            ->getQuery();
//
//        $friends1 = $query->getResult();
//
//        $query = $this->getDoctrine()
//            ->getRepository('AppBundle:Friend')
//            ->createQueryBuilder('f')
//            ->where('f.userid2 = :user2')
//            ->setParameter('user2', $this->getUser() )
//            ->getQuery();
//
//        $friends2 = $query->getResult();

        $friends = [];

        foreach($friends1 as $f)
        {
            if(empty($friends[$f->getUserid2()->getId()])) {
                $friends[$f->getUserid2()->getId()] = [];
            }

            $friends[$f->getUserId2()->getId()]['friend1'] = $f;
        }

        foreach($friends2 as $f)
        {
            if(empty($friends[$f->getUserid1()->getId()])) {
                $friends[$f->getUserid1()->getId()] = [];
            }

            $friends[$f->getUserId1()->getId()]['friend2'] = $f;
        }

        foreach ($friends as $key => $f)
        {
            if(!empty($f['friend1']) && !empty($f['friend2']))
            {
                $friends[$key] = $f['friend1']->getUserid2();
            } else {
                unset($friends[$key]);
            }
        }

        return $this->render('user/friends/myfriends.html.twig',
            [
                'friends' => $friends
            ]
        );
    }

    /**
     * @Route("/user/friends/search", name="user_friends_search")
     */
    public function searchAction(Request $request)
    {

        $searchParams = $request->query->all();
        $session = $request->getSession();
        $session->set('searchParams', $searchParams);

        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->createQueryBuilder('f')
            ->where('f.userid1 = :user1')
            ->setParameter('user1', $this->getUser() )
            ->getQuery();

        $friends1 = $query->getResult();

        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->createQueryBuilder('f')
            ->where('f.userid2 = :user2')
            ->setParameter('user2', $this->getUser() )
            ->getQuery();

        $friends2 = $query->getResult();

        $friends = [];

        foreach($friends1 as $f)
        {
            if(empty($friends[$f->getUserid2()->getId()])) {
                $friends[$f->getUserid2()->getId()] = [];
            }

            $friends[$f->getUserid2()->getId()]['friend1'] = $f;
        }

        foreach($friends2 as $f)
        {
            if(empty($friends[$f->getUserid1()->getId()])) {
                $friends[$f->getUserid1()->getId()] = [];
            }

            $friends[$f->getUserid1()->getId()]['friend2'] = $f;
        }

        $table = $this->get('jgm.table')->createTable(new FriendsSearchTableType($friends, $this->getUser()->getId()));

        return $this->render('user/friends/search.html.twig',
            [
                'userTable' => $table->createView()
            ]
        );
    }

    /**
     * @Route("/user/friends/invitations", name="user_friends_invitations")
     */
    public function invitationsAction(Request $request)
    {
        $friends1 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['userid1' =>  $this->getUser()])
        ;
        $friends2 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['userid2' =>  $this->getUser()])
        ;

        $friends = [];

        foreach($friends1 as $f)
        {
            if(empty($friends[$f->getUserid2()->getId()])) {
                $friends[$f->getUserid2()->getId()] = [];
            }

            $friends[$f->getUserId2()->getId()]['friend1'] = $f;
        }

        foreach($friends2 as $f)
        {
            if(empty($friends[$f->getUserid1()->getId()])) {
                $friends[$f->getUserid1()->getId()] = [];
            }

            $friends[$f->getUserId1()->getId()]['friend2'] = $f;
        }

        foreach ($friends as $key => $f)
        {
            if(empty($f['friend1']) && !empty($f['friend2']))
            {
                $friends[$key] = $f['friend2']->getUserid1();
            } else {
                unset($friends[$key]);
            }
        }

        return $this->render('user/friends/invitations.html.twig',
            [
                'friends' => $friends
            ]
        );
    }


    /**
     * @Route("/user/friends/removeFriend/{userId}/{redirected}", name="user_friends_removeFriend")
     */
    public function removeFriendAction($userId, $redirected = 'profile', Request $request)
    {
        if ($userId == $this->getUser()->getId()) {
            $this->addFlash(
                'error',
                'This person is you.'
            );
        } else {

            $friend1 = $this->getDoctrine()
                ->getRepository('AppBundle:Friend')
                ->findOneBy(['userid1' => $this->getUser(), 'userid2' => $userId]);

            if (!empty($friend1)) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($friend1);
                $em->flush();
            }

            $friend2 = $this->getDoctrine()
                ->getRepository('AppBundle:Friend')
                ->findOneBy(['userid1' => $userId, 'userid2' => $this->getUser()]);

            if (!empty($friend2)) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($friend2);
                $em->flush();
            }

            $this->addFlash(
                'notice',
                'Friend have been removed.'
            );
        }

        if($redirected == 'profile')
        {
            return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
        } elseif($redirected == 'search') {
            $session = $request->getSession();
            $searchParams = $session->get('searchParams');

            return $this->redirectToRoute('user_friends_search', $searchParams);
        }
    }


    /**
     * @Route("/user/friends/sendInvitations/{userId}/{redirected}", name="user_friends_sendInvitations")
     */
    public function sendInvitationsAction($userId, $redirected = 'profile', Request $request)
    {
        if ($userId == $this->getUser()->getId()) {
            $this->addFlash(
                'error',
                'This user is you.'
            );

            return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
        }

        $user1 = $this->getUser();
        $user2 = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $friend = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['userid1' => $this->getUser(), 'userid2' => $user2]);

        if (empty($friend)) {
            $friend = new Friend();
            $friend->setUserid1($user1);
            $friend->setUserid2($user2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($friend);
            $em->flush();

            $this->addFlash(
                'notice',
                'Invitation have been send.'
            );
        } else {
            $this->addFlash(
                'error',
                'This user is your friend.'
            );
        }

        if($redirected == 'profile')
        {
            return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
        } elseif($redirected == 'search') {
            $session = $request->getSession();
            $searchParams = $session->get('searchParams');

            return $this->redirectToRoute('user_friends_search', $searchParams);
        }
    }

    /**
     * @Route("/user/friends/confirmInvitation/{userId}/{redirected}", name="user_friends_confirmInvitation")
     */
    public function confirmInvitationAction($userId, $redirected = 'profile', Request $request)
    {
        $user1 = $this->getUser();
        $user2 = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $friend = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['userid1' => $user2, 'userid2' => $user1]);

        if (!empty($friend)) {

            $friend1 = new Friend();
            $friend1->setUserid1($user1);
            $friend1->setUserid2($user2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($friend1);
            $em->flush();

            $this->addFlash(
                'notice',
                'Invitation have been confirmed.'
            );
        } else {
            $this->addFlash(
                'error',
                'This user did not send invitation to you.'
            );
        }

        if($redirected == 'profile')
        {
            return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
        } elseif($redirected == 'search') {
            $session = $request->getSession();
            $searchParams = $session->get('searchParams');

            return $this->redirectToRoute('user_friends_search', $searchParams);
        }
    }
}