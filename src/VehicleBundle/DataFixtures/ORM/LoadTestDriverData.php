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
class LoadTestDriverData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
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
        // Creating general drivers
        $i = 0;
        while ($i < 10) {
            $vehicle = $this->getReference('test-vehicle-1' . $i);
            $user = $this->getReference('test-user-0');
            $vehicle->addDriver($user);
            $manager->persist($vehicle);
            $manager->flush();

            $vehicle = $this->getReference('test-vehicle-0' . $i);
            $user = $this->getReference('test-user-1');
            $vehicle->addDriver($user);
            $manager->persist($vehicle);
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
        return 3;
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