<?php

namespace VehicleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use VehicleBundle\Entity\Vehicle;

/*
 * For loading vehicle entries to the test database
 *
 * Only used in test environment
 */
class LoadTestVehicleData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Loads the vehicle objects for testing
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        // Creating general vehicles
        $i = 0;
        while ($i < 10) {
            $j = 0;
            while ($j < 10) {
                $testVehicle = (new Vehicle())
                    ->setLicensePlateNo('TEST-LPN' . ($i * 10 + $j))
                    ->setName('testVehicle' . ($i * 10 + $j))
                    ->setDescription('testDescription' . ($i * 10 + $j))
                    ->setFuelOne('testFuelOne' . ($i * 10 + $j))
                    ->setFuelTwo('testFuelTwo' . ($i * 10 + $j))
                    ->setMake('testMake' . ($i * 10 + $j))
                    ->setModel('testModel' . ($i * 10 + $j))
                    ->setYear(1900 + $i * 10 + $j)
                    ->setOwner($this->getReference('test-user-' . $i));
                $manager->persist($testVehicle);
                $manager->flush();
                $this->addReference('test-vehicle-' . ($i * 10 + $j), $testVehicle);
                $j++;
            }
            $i++;
        }
    }

    /**
     * Returns the order of persisting vehicle entities
     *
     * @return int
     */
    public function getOrder() {
        return 2;
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