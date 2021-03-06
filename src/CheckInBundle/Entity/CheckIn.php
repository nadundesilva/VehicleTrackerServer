<?php

namespace CheckInBundle\Entity;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use CoreBundle\Entity\User;
use VehicleBundle\Entity\Vehicle;

/**
 * CheckIn
 */
class CheckIn
{
    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $description;

    /**
     * @SerializedName("latitude")
     * @Groups({"view"})
     * @var string
     */
    private $lat;

    /**
     * @SerializedName("longitude")
     * @Groups({"view"})
     * @var string
     */
    private $long;

    /**
     * @Groups({"list", "view"})
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @Groups({"list", "view"})
     * @var integer
     */
    private $id;

    /**
     * @var Vehicle
     */
    private $vehicle;

    /**
     * @var User
     */
    private $creator;
    
    public function __construct()
    {
        $this->timestamp = new \DateTime();
    }


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
     * @param Vehicle $vehicle
     *
     * @return CheckIn
     */
    public function setVehicle(Vehicle $vehicle = null)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set creator
     *
     * @param User $creator
     *
     * @return CheckIn
     */
    public function setCreator(User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @VirtualProperty
     * @SerializedName("creator")
     * @Groups({"list", "view"})
     */
    public function getCreatorID()
    {
        return $this->creator->getUsername();
    }

    /**
     * @VirtualProperty
     * @SerializedName("vehicle_name")
     * @Groups({"view"})
     */
    public function getVehicleName()
    {
        return $this->vehicle->getName();
    }

    /**
     * @VirtualProperty
     * @SerializedName("vehicle_license_plate_no")
     * @Groups({"view"})
     */
    public function getVehicleLicensePlateNo()
    {
        return $this->vehicle->getLicensePlateNo();
    }
}

