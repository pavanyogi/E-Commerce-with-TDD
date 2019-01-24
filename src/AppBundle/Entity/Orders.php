<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Customer;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdersRepository")
 */
class Orders
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="order_id", type="string", length=255, unique = true)
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="agent_id", type="integer", nullable=true)
     */
    private $agentId;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     */
    private $customerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="booked_date", type="datetime")
     */
    private $bookedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date_time", type="datetime")
     */
    private $createdDateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date_time", type="datetime")
     */
    private $lastUpdateDateTime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderId
     *
     * @param string $orderId
     *
     * @return Orders
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Orders
     */
    public function setAgentId($agentId)
    {
        $this->agentId = $agentId;

        return $this;
    }

    /**
     * Get agentId
     *
     * @return int
     */
    public function getagentId()
    {
        return $this->agentId;
    }

    /**
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Orders
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set bookedDate
     *
     * @param \DateTime $bookedDate
     *
     * @return Orders
     */
    public function setBookedDate($bookedDate)
    {
        $this->bookedDate = $bookedDate;

        return $this;
    }

    /**
     * Get bookedDate
     *
     * @return \DateTime
     */
    public function getBookedDate()
    {
        return $this->bookedDate;
    }

    /**
     * Set createdDateTime
     *
     * @param \DateTime $createdDateTime
     *
     * @return Orders
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;

        return $this;
    }

    /**
     * Get createdDateTime
     *
     * @return \DateTime
     */
    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    /**
     * Set lastUpdateDateTime
     *
     * @param \DateTime $lastUpdateDateTime
     *
     * @return Orders
     */
    public function setLastUpdateDateTime($lastUpdateDateTime)
    {
        $this->lastUpdateDateTime = $lastUpdateDateTime;

        return $this;
    }

    /**
     * Get lastUpdateDateTime
     *
     * @return \DateTime
     */
    public function getLastUpdateDateTime()
    {
        return $this->lastUpdateDateTime;
    }

    /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdDateTime = new \DateTime("now");
        $this->lastUpdateDateTime = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->lastUpdateDateTime = new \DateTime("now");
    }
}

