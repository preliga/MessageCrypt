<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-08-13
 * Time: 16:50
 */

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Friend;
use AppBundle\Entity\Message;
use AppBundle\Resources\Controller\BaseUserController;

/**
 * @Route("/user/messages")
 */
class MessagesController extends BaseUserController
{
    /**
     * @Route("/showAll/{friendId}", name="user_messages_showAll")
     */
    public function showAllAction($friendId, Request $request)
    {
        if(!$this->isFriend($friendId))
        {
            $this->addFlash(
                'Error',
                'This user is not Your friend.'
            );

            return $this->redirectToRoute('user_profile_profile',
                [
                    'id' => $friendId
                ]
            );
        }

        $author = $this->getUser();
        $recipient = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($friendId);



        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
            ->createQueryBuilder('m')
            ->where('(m.author = :author AND m.recipient = :recipient) OR (m.recipient = :author AND m.author = :recipient)')
            ->orderBy('m.date', 'DESC')
            ->setParameter('author', $author )
            ->setParameter('recipient', $recipient )
            ->getQuery();

        $messages = $query->getResult();



        foreach ($messages as $msg){
            if($msg->getAuthor() != $this->getUser()) {
                $msg->setRead(true);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->render('user/messages/showAll.html.twig',
            [
                'friendId' => $friendId,
                'messages' => $messages
            ]
        );
    }

    /**
     * @Route("/messenger", name="user_messages_messenger")
     */
    public function messengerAction(Request $request)
    {
        return $this->render('user/messages/messenger.html.twig',
            [
            ]
        );
    }

        /**
     * @Route("/sendMessage/{friendId}", name="user_messages_sendMessage")
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


            $author = $this->getUser();
            $recipient = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($friendId);


            $message = new Message();
            $message->setText($text);
            $message->setAuthor($author);
            $message->setRecipient($recipient);

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


        return $this->redirectToRoute('user_messages_showAll',
            [
                'friendId' => $friendId
            ]
        );
    }

    private function isFriend($friendId)
    {
        $friend1 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findOneBy(['author' =>  $this->getUser(), 'recipient' => $friendId ])
        ;
        $friend2 = $this->getDoctrine()->getManager()
            ->getRepository(Friend::class)
            ->findOneBy(['author' => $friendId,'recipient' =>  $this->getUser()])
        ;

        return !empty($friend1) && !empty($friend2);
    }
}