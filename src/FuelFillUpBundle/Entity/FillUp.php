<?php

namespace FuelFillUpBundle\Entity;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use CoreBundle\Entity\User;
use VehicleBundle\Entity\Vehicle;

/**
 * FillUp
 */
class FillUp
{
    /**
     * @Groups({"view"})
     * @var float
     */
    private $odoMeterReading;

    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $litres;

    /**
     * @Groups({"list", "view"})
     * @var string
     */
    private $price;

    /**
     * @Groups({"view"})
     * @var string
     */
    private $stationLat;

    /**
     * @Groups({"view"})
     * @var string
     */
    private $stationLong;

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
     * Set odoMeterReading
     *
     * @param integer $odoMeterReading
     *
     * @return FillUp
     */
    public function setOdoMeterReading($odoMeterReading)
    {
        $this->odoMeterReading = $odoMeterReading;

        return $this;
    }

    /**
     * Get odoMeterReading
     *
     * @return integer
     */
    public function getOdoMeterReading()
    {
        return $this->odoMeterReading;
    }

    /**
     * Set litres
     *
     * @param string $litres
     *
     * @return FillUp
     */
    public function setLitres($litres)
    {
        $this->litres = $litres;

        return $this;
    }

    /**
     * Get litres
     *
     * @return string
     */
    public function getLitres()
    {
        return $this->litres;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return FillUp
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stationLat
     *
     * @param string $stationLat
     *
     * @return FillUp
     */
    public function setStationLat($stationLat)
    {
        $this->stationLat = $stationLat;

        return $this;
    }

    /**
     * Get stationLat
     *
     * @return string
     */
    public function getStationLat()
    {
        return $this->stationLat;
    }

    /**
     * Set stationLong
     *
     * @param string $stationLong
     *
     * @return FillUp
     */
    public function setStationLong($stationLong)
    {
        $this->stationLong = $stationLong;

        return $this;
    }

    /**
     * Get stationLong
     *
     * @return string
     */
    public function getStationLong()
    {
        return $this->stationLong;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return FillUp
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
     * @return FillUp
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
     * @return FillUp
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
     * @SerializedName("vehicle")
     * @Groups({"view"})
     */
    public function getVehicleLicensePlateNo()
    {
        return $this->vehicle->getLicensePlateNo();
    }
}

