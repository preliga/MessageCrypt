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
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()
            ->from('AppBundle:Friend', 'f1')
            ->select("f1")
            ->leftJoin("AppBundle:Friend", "f2", "WITH", "f1.author = f2.recipient and f1.recipient = f2.author")
            ->where("f1.recipient = :recipient and f2.id is null")
            ->setParameter('recipient', $this->getUser() )
            ->getQuery();

        $result = $query->getResult();

        $countInvitations = count($result);

        $this->twig->addGlobal('countInvitations', $countInvitations);
    }

}
