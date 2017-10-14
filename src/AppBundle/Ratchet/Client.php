<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-10-02
 * Time: 20:58
 */

namespace AppBundle\Ratchet;

use AppBundle\Entity\User;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Client
{
    public $connection;

    public $user;

    protected $container;

    public function __construct(ContainerInterface $container, ConnectionInterface $connection, String $authToken)
    {
        $this->connection = $connection;
        $this->container = $container;

        $em = $this->container->get('doctrine.orm.entity_manager');
        $this->user = $em
            ->getRepository(User::class)
            ->findOneBy(['token' => $authToken]);

    }

    public function getUsersToMessages()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $id = $this->user->getId();

        $connection = $em->getConnection();
        $query = $connection->prepare("
            SELECT
              msg.id,
              u.name,
              u.lastname,
              u.avatar,
              IFNULL(r.`read`,1) as 'read',
              msg.d
            FROM
              (
                SELECT
                  m.author AS id,
                  m.date AS d
                FROM message AS m
                UNION ALL
                Select
                  m.recipient AS id,
                  m.date AS d
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
              msg.id <> $id
            GROUP BY
              msg.id
            ORDER BY
              msg.d DESC
        ");

        $query->execute();
        $result = $query->fetchAll();

        return $result;
    }

    public function getInvitations()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
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

    public function getUser()
    {
        return $this->user;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}