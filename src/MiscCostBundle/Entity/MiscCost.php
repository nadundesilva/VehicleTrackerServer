<?php

namespace MiscCostBundle\Entity;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use CoreBundle\Entity\User;
use VehicleBundle\Entity\Vehicle;

/**
 * MiscCost
 */
class MiscCost
{
    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $type;

    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $value;

    /**
     * @Groups({"view"})
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
     * Set type
     *
     * @param string $type
     *
     * @return MiscCost
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return MiscCost
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return MiscCost
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
     * @return MiscCost
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
     * @return MiscCost
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

