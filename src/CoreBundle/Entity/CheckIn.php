<?php

namespace CoreBundle\Entity;

/**
 * CheckIn
 */
class CheckIn
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $lat;

    /**
     * @var string
     */
    private $long;

    /**
     * @var \DateTime
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoreBundle\Entity\Vehicle
     */
    private $vehicle;

    /**
     * @var \CoreBundle\Entity\User
     */
    private $creator;


    /**
     * Set description
     *
     * @param string $description
     *
     * @return CheckIn
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
     * Set lat
     *
     * @param string $lat
     *
     * @return CheckIn
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set long
     *
     * @param string $long
     *
     * @return CheckIn
     */
    public function setLong($long)
    {
        $this->long = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return string
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return CheckIn
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set vehicle
     *
     * @param \CoreBundle\Entity\Vehicle $vehicle
     *
     * @return CheckIn
     */
    public function setVehicle(\CoreBundle\Entity\Vehicle $vehicle = null)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return \CoreBundle\Entity\Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set creator
     *
     * @param \CoreBundle\Entity\User $creator
     *
     * @return CheckIn
     */
    public function setCreator(\CoreBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \CoreBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }
}

