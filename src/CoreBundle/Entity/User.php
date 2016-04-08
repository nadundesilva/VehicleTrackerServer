<?php

namespace CoreBundle\Entity;

/**
 * User
 */
class User
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $username;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vehicle;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vehicle = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Add vehicle
     *
     * @param \CoreBundle\Entity\Vehicle $vehicle
     *
     * @return User
     */
    public function addVehicle(\CoreBundle\Entity\Vehicle $vehicle)
    {
        $this->vehicle[] = $vehicle;

        return $this;
    }

    /**
     * Remove vehicle
     *
     * @param \CoreBundle\Entity\Vehicle $vehicle
     */
    public function removeVehicle(\CoreBundle\Entity\Vehicle $vehicle)
    {
        $this->vehicle->removeElement($vehicle);
    }

    /**
     * Get vehicle
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }
}

