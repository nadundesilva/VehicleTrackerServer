<?php

namespace CheckInBundle\DataFixtures\ORM;

use CheckInBundle\Entity\CheckIn;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FuelFillUpBundle\Entity\FillUp;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * For loading vehicle entries to the test database
 *
 * Only used in test environment
 */
class LoadTestFuelFillUpData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Loads the fuel fill up objects for testing
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        // Creating general fuel fill ups
        $i = 0;
        while ($i < 10) {
            $check_in = (new FillUp())
                ->setOdoMeterReading(40000)
                ->setLitres(10 + $i)
                ->setPrice(80.64 + $i)
                ->setStationLat(7.6546 + $i)
                ->setStationLong(80.6451 + $i)
                ->setTimestamp(new \DateTime())
                ->setVehicle($this->getReference('test-vehicle-1' . $i))
                ->setCreator($this->getReference('test-user-0'));
            $manager->persist($check_in);
            $manager->flush();
            $i++;
        }

        $i = 0;
        while ($i < 10) {
            $check_in = (new FillUp())
                ->setOdoMeterReading(40000)
                ->setLitres(10 + $i)
                ->setPrice(80.64 + $i)
                ->setStationLat(7.6546 + $i)
                ->setStationLong(80.6451 + $i)
                ->setTimestamp(new \DateTime())
                ->setVehicle($this->getReference('test-vehicle-1' . $i))
                ->setCreator($this->getReference('test-user-1'));
            $manager->persist($check_in);
            $manager->flush();
            $i++;
        }

        $i = 0;
        while ($i < 10) {
            $check_in = (new FillUp())
                ->setOdoMeterReading(40000)
                ->setLitres(10 + $i)
                ->setPrice(80.64 + $i)
                ->setStationLat(7.6546 + $i)
                ->setStationLong(80.6451 + $i)
                ->setTimestamp(new \DateTime())
                ->setVehicle($this->getReference('test-vehicle-2' . $i))
                ->setCreator($this->getReference('test-user-1'));
            $manager->persist($check_in);
            $manager->flush();
            $i++;
        }
    }

    /**
     * Returns the order of persisting vehicle entities
     *
     * @return int
     */
    public function getOrder() {
        return 4;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
}