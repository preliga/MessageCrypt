<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Message
 *
 * @ORM\Table(name="message", uniqueConstraints={@ORM\UniqueConstraint(name="userId1_userId2", columns={"userId1", "userId2"})}, indexes={@ORM\Index(name="message_user2_user_id", columns={"userId2"}), @ORM\Index(name="IDX_B6BD307F116AD761", columns={"userId1"})})
 * @ORM\Entity
 */

class Message
{
    public function __construct()
    {
        $this->date = new DateTimeNow();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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


    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $id
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUserid1(): User
    {
        return $this->userid1;
    }

    /**
     * @param User $userid1
     */
    public function setUserid1(User $userid1)
    {
        $this->userid1 = $userid1;
    }

    /**
     * @return User
     */
    public function getUserid2(): User
    {
        return $this->userid2;
    }

    /**
     * @param User $userid2
     */
    public function setUserid2(User $userid2)
    {
        $this->userid2 = $userid2;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

}

