<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friend
 *
 * @ORM\Table(name="friend", uniqueConstraints={@ORM\UniqueConstraint(name="userId1_userId2", columns={"userId1", "userId2"})}, indexes={@ORM\Index(name="friend_status_frienddictionary_id", columns={"status"}), @ORM\Index(name="frined_user2_user_id", columns={"userId2"}), @ORM\Index(name="IDX_55EEAC61116AD761", columns={"userId1"})})
 * @ORM\Entity
 */
class Friend
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Frienddictionary
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Frienddictionary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId1", referencedColumnName="id")
     * })
     */
    private $userid1;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId2", referencedColumnName="id")
     * })
     */
    private $userid2;


}

