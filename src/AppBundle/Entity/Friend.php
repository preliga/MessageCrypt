<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friend
 *
 * @ORM\Table(name="friend", uniqueConstraints={@ORM\UniqueConstraint(name="author_recipient", columns={"author", "recipient"})}, indexes={@ORM\Index(name="friend_recipient_id", columns={"recipient"}), @ORM\Index(name="friend_author_id", columns={"author"})})
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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author", referencedColumnName="id")
     * })
     */
    private $author;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipient", referencedColumnName="id")
     * })
     */
    private $recipient;

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
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getRecipient(): User
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     */
    public function setRecipient(User $recipient)
    {
        $this->userid2 = $userid2;
    }


}

