<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="product_code", type="string", length=255, unique=true)
     */
    private $productCode;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255, nullable=true)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="product_description", type="string", length=255, nullable=true)
     */
    private $productDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $quantity;

    /**
     * @var mixed
     *
     * @ORM\Column(name="price_per_unit", type="decimal", precision=30, scale=2, nullable=true)
     */
    private $pricePerUnit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="stock_avialable", type="boolean", nullable=true)
     */
    private $stockAvialable;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date_time", type="datetime", nullable=true)
     */
    private $createdDateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated_date_time", type="datetime", nullable=true)
     */
    private $lastUpdatedDateTime;


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
     * Set productCode
     *
     * @param string $productCode
     *
     * @return Product
     */
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;

        return $this;
    }

    /**
     * Get productCode
     *
     * @return string
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set productDescription
     *
     * @param string $productDescription
     *
     * @return Product
     */
    public function setProductDescription($productDescription)
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    /**
     * Get productDescription
     *
     * @return string
     */
    public function getProductDescription()
    {
        return $this->productDescription;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set pricePerUnit
     *
     * @param float $pricePerUnit
     *
     * @return Product
     */
    public function setPricePerUnit($pricePerUnit)
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    /**
     * Get pricePerUnit
     *
     * @return float
     */
    public function getPricePerUnit()
    {
        return $this->pricePerUnit;
    }

    /**
     * Set stockAvialable
     *
     * @param boolean $stockAvialable
     *
     * @return Product
     */
    public function setStockAvialable($stockAvialable)
    {
        $this->stockAvialable = $stockAvialable;

        return $this;
    }

    /**
     * Get stockAvialable
     *
     * @return boolean
     */
    public function getStockAvialable()
    {
        return $this->stockAvialable;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Product
     */
    public function setUnit(string $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set createdDateTime
     *
     * @param \DateTime $createdDateTime
     *
     * @return Product
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
     * Set lastUpdatedDateTime
     *
     * @param \DateTime $lastUpdatedDateTime
     *
     * @return Product
     */
    public function setLastUpdatedDateTime($lastUpdatedDateTime)
    {
        $this->lastUpdatedDateTime = $lastUpdatedDateTime;

        return $this;
    }

    /**
     * Get lastUpdatedDateTime
     *
     * @return \DateTime
     */
    public function getLastUpdatedDateTime()
    {
        return $this->lastUpdatedDateTime;
    }

    /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->status = 1;
        $this->createdDateTime = new \DateTime("now");
        $this->lastUpdatedDateTime = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->lastUpdatedDateTime = new \DateTime("now");
    }
}

