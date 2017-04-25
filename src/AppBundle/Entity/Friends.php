<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friends
 *
 * @ORM\Table(name="friends")
 * @ORM\Entity
 */
class Friends
{
    /**
     * @var integer
     *
     * @ORM\Column(name="friendship_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $friendshipId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_a", type="integer", nullable=false)
     */
    private $userA;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_b", type="integer", nullable=false)
     */
    private $userB;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp;



    /**
     * Set userA
     *
     * @param integer $userA
     *
     * @return Friends
     */
    public function setUserA($userA)
    {
        $this->userA = $userA;

        return $this;
    }

    /**
     * Get userA
     *
     * @return integer
     */
    public function getUserA()
    {
        return $this->userA;
    }

    /**
     * Set userB
     *
     * @param integer $userB
     *
     * @return Friends
     */
    public function setUserB($userB)
    {
        $this->userB = $userB;

        return $this;
    }

    /**
     * Get userB
     *
     * @return integer
     */
    public function getUserB()
    {
        return $this->userB;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Friends
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get friendshipId
     *
     * @return integer
     */
    public function getFriendshipId()
    {
        return $this->friendshipId;
    }
}
