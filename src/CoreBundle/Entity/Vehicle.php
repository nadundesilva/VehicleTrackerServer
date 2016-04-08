<?php

namespace CoreBundle\Entity;

/**
 * Vehicle
 */
class Vehicle
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $fuelOne;

    /**
     * @var string
     */
    private $fuelTwo;

    /**
     * @var string
     */
    private $make;

    /**
     * @var string
     */
    private $model;

    /**
     * @var \DateTime
     */
    private $year;

    /**
     * @var string
     */
    private $licensePlateNo;

    /**
     * @var \CoreBundle\Entity\User
     */
    private $owner;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \DateTime $year
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
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
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
     * @param \CoreBundle\Entity\User $owner
     *
     * @return Vehicle
     */
    public function setOwner(\CoreBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \CoreBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return Vehicle
     */
    public function addUser(\CoreBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \CoreBundle\Entity\User $user
     */
    public function removeUser(\CoreBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }
}

