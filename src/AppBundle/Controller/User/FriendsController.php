<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 17:04
 */

namespace AppBundle\Controller\User;

use AppBundle\Entity\Friend;
use AppBundle\Table\FriendsSearchTableType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Resources\Controller\BaseUserController;

/**
 * @Route("/user/friends")
 */
class FriendsController extends BaseUserController
{
    /**
     * @Route("/myfriends", name="user_friends_myfriends")
     */
    public function myfriendsAction(Request $request)
    {
        $friends1 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['author' =>  $this->getUser()])
        ;
        $friends2 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['recipient' =>  $this->getUser()])
        ;

        $friends = [];

        foreach($friends1 as $f)
        {
            if(empty($friends[$f->getRecipient()->getId()])) {
                $friends[$f->getRecipient()->getId()] = [];
            }

            $friends[$f->getRecipient()->getId()]['friend1'] = $f;
        }

        foreach($friends2 as $f)
        {
            if(empty($friends[$f->getAuthor()->getId()])) {
                $friends[$f->getAuthor()->getId()] = [];
            }

            $friends[$f->getAuthor()->getId()]['friend2'] = $f;
        }

        foreach ($friends as $key => $f)
        {
            if(!empty($f['friend1']) && !empty($f['friend2']))
            {
                $friends[$key] = $f['friend1']->getRecipient();
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
     * @Route("/search", name="user_friends_search")
     */
    public function searchAction(Request $request)
    {

        $searchParams = $request->query->all();
        $session = $request->getSession();
        $session->set('searchParams', $searchParams);

        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->createQueryBuilder('f')
            ->where('f.author = :author')
            ->setParameter('author', $this->getUser() )
            ->getQuery();

        $friends1 = $query->getResult();

        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->createQueryBuilder('f')
            ->where('f.recipient = :recipient')
            ->setParameter('recipient', $this->getUser() )
            ->getQuery();

        $friends2 = $query->getResult();

        $friends = [];

        foreach($friends1 as $f)
        {
            if(empty($friends[$f->getRecipient()->getId()])) {
                $friends[$f->getRecipient()->getId()] = [];
            }

            $friends[$f->getRecipient()->getId()]['friend1'] = $f;
        }

        foreach($friends2 as $f)
        {
            if(empty($friends[$f->getAuthor()->getId()])) {
                $friends[$f->getAuthor()->getId()] = [];
            }

            $friends[$f->getAuthor()->getId()]['friend2'] = $f;
        }

        $table = $this->get('jgm.table')->createTable(new FriendsSearchTableType($friends, $this->getUser()->getId()));

        return $this->render('user/friends/search.html.twig',
            [
                'userTable' => $table->createView()
            ]
        );
    }

    /**
     * @Route("/invitations", name="user_friends_invitations")
     */
    public function invitationsAction(Request $request)
    {
        $friends1 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['author' =>  $this->getUser()])
        ;
        $friends2 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findBy(['recipient' =>  $this->getUser()])
        ;

        $friends = [];

        foreach($friends1 as $f)
        {
            if(empty($friends[$f->getRecipient()->getId()])) {
                $friends[$f->getRecipient()->getId()] = [];
            }

            $friends[$f->getRecipient()->getId()]['friend1'] = $f;
        }

        foreach($friends2 as $f)
        {
            if(empty($friends[$f->getAuthor()->getId()])) {
                $friends[$f->getAuthor()->getId()] = [];
            }

            $friends[$f->getAuthor()->getId()]['friend2'] = $f;
        }

        foreach ($friends as $key => $f)
        {
            if(empty($f['friend1']) && !empty($f['friend2']))
            {
                $friends[$key] = $f['friend2']->getAuthor();
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
     * @Route("/removeFriend/{userId}/{redirected}", name="user_friends_removeFriend")
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
                ->findOneBy(['author' => $this->getUser(), 'recipient' => $userId]);

            if (!empty($friend1)) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($friend1);
                $em->flush();
            }

            $friend2 = $this->getDoctrine()
                ->getRepository('AppBundle:Friend')
                ->findOneBy(['author' => $userId, 'recipient' => $this->getUser()]);

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
     * @Route("/sendInvitations/{userId}/{redirected}", name="user_friends_sendInvitations")
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

        $author = $this->getUser();
        $recipient = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $friend = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['author' => $this->getUser(), 'recipient' => $recipient]);

        if (empty($friend)) {
            $friend = new Friend();
            $friend->setAuthor($author);
            $friend->setRecipient($recipient);

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
     * @Route("/confirmInvitation/{userId}/{redirected}", name="user_friends_confirmInvitation")
     */
    public function confirmInvitationAction($userId, $redirected = 'profile', Request $request)
    {
        $recipient = $this->getUser();
        $author = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $friend = $this->getDoctrine()
            ->getRepository('AppBundle:Friend')
            ->findOneBy(['author' => $author, 'recipient' => $recipient]);

        if (!empty($friend)) {

            $friend1 = new Friend();
            $friend1->setAuthor($recipient);
            $friend1->setRecipient($author);

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