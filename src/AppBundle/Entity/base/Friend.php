<?php

namespace AppBundle\Entity\base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friend
 *
 * @ORM\Table(name="friend", uniqueConstraints={@ORM\UniqueConstraint(name="userId1_userId2", columns={"userId1", "userId2"})}, indexes={@ORM\Index(name="friend_status_frienddictionary_id", columns={"status"})})
 * @ORM\Entity
 */
class Friend
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userId1", type="integer", nullable=true)
     */
    protected $userid1;

    /**
     * @var integer
     *
     * @ORM\Column(name="userId2", type="integer", nullable=true)
     */
    protected $userid2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @return int
     */
    public function getUserid1()
    {
        return $this->userid1;
    }

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId1", referencedColumnName="id")
     * })
     */
    public function setUserid1($userid1)
    {
        $this->userid1 = $userid1;
    }

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId2", referencedColumnName="id")
     * })
     */
    public function getUserid2()
    {
        return $this->userid2;
    }

    /**
     * @param int $userid2
     */
    public function setUserid2($userid2)
    {
        $this->userid2 = $userid2;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}

