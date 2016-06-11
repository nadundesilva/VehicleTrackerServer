<?php

namespace VehicleBundle\Entity;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use CoreBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Vehicle
 */
class Vehicle
{
    /**
     * @Groups({"list", "view", "names_list"})
     * @var string
     */
    private $name;

    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $description;

    /**
     * @SerializedName("fuel_one")
     * @Groups({"view"})
     * @var string
     */
    private $fuelOne;

    /**
     * @SerializedName("fuel_two")
     * @Groups({"view"})
     * @var string
     */
    private $fuelTwo;

    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $make;

    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $model;

    /**
     * @Groups({"list", "view"})
     * @var integer
     */
    private $year;

    /**
     * @SerializedName("license_plate_no")
     * @Groups({"list", "view", "names_list"})
     * @var string
     */
    private $licensePlateNo;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var Collection
     */
    private $driver;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->driver = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Vehicle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Vehicle
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fuelOne
     *
     * @param string $fuelOne
     *
     * @return Vehicle
     */
    public function setFuelOne($fuelOne)
    {
        $this->fuelOne = $fuelOne;

        return $this;
    }

    /**
     * Get fuelOne
     *
     * @return string
     */
    public function getFuelOne()
    {
        return $this->fuelOne;
    }

    /**
     * Set fuelTwo
     *
     * @param string $fuelTwo
     *
     * @return Vehicle
     */
    public function setFuelTwo($fuelTwo)
    {
        $this->fuelTwo = $fuelTwo;

        return $this;
    }

    /**
     * Get fuelTwo
     *
     * @return string
     */
    public function getFuelTwo()
    {
        return $this->fuelTwo;
    }

    /**
     * Set make
     *
     * @param string $make
     *
     * @return Vehicle
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Vehicle
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Vehicle
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set licensePlateNo
     *
     * @param string $licensePlateNo
     *
     * @return Vehicle
     */
    public function setLicensePlateNo($licensePlateNo)
    {
        $this->licensePlateNo = $licensePlateNo;

        return $this;
    }

    /**
     * Get licensePlateNo
     *
     * @return string
     */
    public function getLicensePlateNo()
    {
        return $this->licensePlateNo;
    }

    /**
     * Set owner
     *
     * @param User $owner
     *
     * @return Vehicle
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add driver
     *
     * @param User $driver
     *
     * @return Vehicle
     */
    public function addDriver(User $driver)
    {
        $this->driver[] = $driver;

        return $this;
    }

    /**
     * Remove driver
     *
     * @param User $driver
     */
    public function removeDriver(User $driver)
    {
        $this->driver->removeElement($driver);
    }

    /**
     * Get driver
     *
     * @return Collection
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @VirtualProperty
     * @SerializedName("owner")
     * @Groups({"list", "view"})
     */
    public function getOwnerID()
    {
        return $this->owner->getUsername();
    }
}

