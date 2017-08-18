<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-08-13
 * Time: 16:50
 */

namespace AppBundle\Controller\User;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Friend;
use AppBundle\Entity\Message;

/**
 * @Route("/user/message/{friendId}")
 */
class MessageController extends Controller
{
    /**
     * @Route("/showAll", name="user_message_showAll")
     */
    public function showAllAction($friendId, Request $request)
    {
        if(!$this->isFriend($friendId))
        {
            $this->addFlash(
                'Error',
                'This user is not Your friend.'
            );

            return $this->render('user/profile/profile.html.twig',
                [
                    'id' => $friendId
                ]
            );
        }

        $user1 = $this->getUser();
        $user2 = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($friendId);



        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
            ->createQueryBuilder('m')
            ->where('(m.userid1 = :user1 AND m.userid2 = :user2) OR (m.userid2 = :user1 AND m.userid1 = :user2)')
            ->orderBy('m.date', 'DESC')
            ->setParameter('user1', $user1 )
            ->setParameter('user2', $user2 )
            ->getQuery();

        $messages = $query->getResult();

        return $this->render('user/message/showAll.html.twig',
            [
                'friendId' => $friendId,
                'messages' => $messages
            ]
        );
    }

    /**
     * @Route("/sendMessage", name="user_message_sendMessage")
     */
    public function sendMessageAction($friendId, Request $request)
    {
        if(!$this->isFriend($friendId))
        {
            $this->addFlash(
                'Error',
                'This user is not Your friend.'
            );

            return $this->render('user/profile/profile.html.twig',
                [
                    'id' => $friendId
                ]
            );
        }

        $post = $request->request->all();

        if(!empty($post['textMessage'])){
            $text = $post['textMessage'];


            $user1 = $this->getUser();
            $user2 = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($friendId);


            $message = new Message();
            $message->setText($text);
            $message->setUserid1($user1);
            $message->setUserid2($user2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash(
                'notice',
                'Send Message'
            );
        }
        else
        {
            $this->addFlash(
                'error',
                'Text is empty.'
            );
        }


        return $this->redirectToRoute('user_message_showAll',
            [
                'friendId' => $friendId
            ]
        );
    }

    private function isFriend($friendId)
    {
        $friend1 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findOneBy(['userid1' =>  $this->getUser(), 'userid2' => $friendId ])
        ;
        $friend2 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findOneBy(['userid1' => $friendId,'userid2' =>  $this->getUser()])
        ;

        return !empty($friend1) && !empty($friend2);
    }
}