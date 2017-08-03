<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 17:04
 */

namespace AppBundle\Controller\User;

//use AppBundle\Entity\User;
use AppBundle\Entity\Friend;
use AppBundle\Entity\Frienddictionary;
use AppBundle\Table\FriendsSearchTableType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FriendsController extends Controller
{
    /**
     * @Route("/user/friends/index", name="user_friends_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('user/friends/index.html.twig',
            [
            ]
        );
    }

    /**
     * @Route("/user/friends/search", name="user_friends_search")
     */
    public function searchAction(Request $request)
    {
//        $friends = $this->getDoctrine()
//            ->getRepository('AppBundle:Friend')
//            ->findOneBy(['userid1' => $this->getUser(), 'userid2' => $userId]);

        $table = $this->get('jgm.table')->createTable(new FriendsSearchTableType([]));

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

        return $this->render('user/friends/invitations.html.twig',
            [
            ]
        );
    }


    /**
     * @Route("/user/friends/removeFriend/{userId}", name="user_friends_removeFriend")
     */
    public function removeFriendAction($userId, Request $request)
    {
        if ($userId == $this->getUser()->getId()) {
            $this->addFlash(
                'error',
                'This person is you.'
            );

            return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
        }

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

        return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
    }


    /**
     * @Route("/user/friends/sendInvitations/{userId}", name="user_friends_sendInvitations")
     */
    public function sendInvitationsAction($userId, Request $request)
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


        return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
    }

    /**
     * @Route("/user/friends/confirmInvitation/{userId}", name="user_friends_confirmInvitation")
     */
    public function confirmInvitationAction($userId, Request $request)
    {
        $user1 = $this->getUser();
        $user2 = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $friend = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['userid1' => $user2), 'userid2' => $user1]);

        if(!empty($friend)) {

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
        return $this->redirectToRoute('user_profile_profile', ['id' => $userId]);
    }
}