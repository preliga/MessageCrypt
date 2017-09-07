<?php

namespace AppBundle\Resources\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseUserController extends Controller
{
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function preAction()
    {
        $countInvitations = count($this->getInvitations());
        $usersToMessages = $this->getUsersToMessages();

        $this->twig->addGlobal('countInvitations', $countInvitations);
        $this->twig->addGlobal('usersToMessages', $usersToMessages);
    }


    private function getUsersToMessages()
    {
        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();
        $query = $connection->prepare("
            SELECT msg.id, u.name, u.lastname, u.avatar
            FROM
            (
                SELECT m.author AS id
                  FROM message AS m
            UNION ALL
                Select m.recipient AS id
                  FROM message AS m
            ) AS msg
            JOIN user as u on u.id = msg.id
            WHERE msg.id <> {$this->getUser()->getId()}
            GROUP BY msg.id
            LIMIT 3
        ");

        $query->execute();
        $result = $query->fetchAll();

        return $result;
    }

    private function getInvitations()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()
            ->from('AppBundle:Friend', 'f1')
            ->select("f1")
            ->leftJoin("AppBundle:Friend", "f2", "WITH", "f1.author = f2.recipient and f1.recipient = f2.author")
            ->where("f1.recipient = :recipient and f2.id is null")
            ->setParameter('recipient', $this->getUser() )
            ->getQuery();

        $result = $query->getResult();

        return $result;
    }

}
