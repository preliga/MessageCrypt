<?php

namespace AppBundle\Resources\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseUserController extends Controller
{
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function preAction()
    {

    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        $this->postAction();
        return parent::render($view, $parameters, $response);
    }

    public function postAction()
    {
        $countInvitations = count($this->getInvitations());
        $usersToMessages = $this->getUsersToMessages();


        $notRead = 0;
        foreach ($usersToMessages as $user)
        {
            if($user['read'] == 0) {
                $notRead++;
            }
        }


        $this->twig->addGlobal('countInvitations', $countInvitations);
        $this->twig->addGlobal('usersToMessages', $usersToMessages);
        $this->twig->addGlobal('notRead', $notRead);
    }


    private function getUsersToMessages()
    {
        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();
        $query = $connection->prepare("
            SELECT 
                msg.id,
                u.name,
                u.lastname,
                u.avatar,
                IFNULL(r.`read`,1) as 'read'
            FROM
            (
                SELECT m.author AS id
                  FROM message AS m
            UNION ALL
                Select m.recipient AS id
                  FROM message AS m
            ) AS msg
            JOIN user AS u ON u.id = msg.id
            LEFT JOIN 
            (
                SELECT 
                    * 
                FROM 
                    message AS m 
                WHERE 
                    m.`read` = 0 
                GROUP BY m.id 
            ) AS r ON r.author = msg.id
            WHERE 
                msg.id <> 1
            GROUP BY 
                msg.id
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
