<?php

namespace CoreBundle\Entity;

/**
 * FillUp
 */
class FillUp
{
    /**
     * @var integer
     */
    private $odoMeterReading;

    /**
     * @var string
     */
    private $litres;

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $stationLat;

    /**
     * @var string
     */
    private $stationLong;

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
     * @param \CoreBundle\Entity\Vehicle $vehicle
     *
     * @return FillUp
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
     * @return FillUp
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

