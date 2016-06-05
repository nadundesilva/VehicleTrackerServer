<?php

namespace MiscCostBundle\DataFixtures\ORM;

use MiscCostBundle\Entity\MiscCost;
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
            $misc_cost = (new MiscCost())
                ->setType('testMiscellaneousCost' . $i)
                ->setValue(1253.50 + $i)
                ->setTimestamp(new \DateTime())
                ->setVehicle($this->getReference('test-vehicle-1' . $i))
                ->setCreator($this->getReference('test-user-0'));
            $manager->persist($misc_cost);
            $manager->flush();
            $i++;
        }

        $i = 0;
        while ($i < 10) {
            $misc_cost = (new MiscCost())
                ->setType('testMiscellaneousCost' . $i)
                ->setValue(1253.50 + $i)
                ->setTimestamp(new \DateTime())
                ->setVehicle($this->getReference('test-vehicle-1' . $i))
                ->setCreator($this->getReference('test-user-1'));
            $manager->persist($misc_cost);
            $manager->flush();
            $i++;
        }

        $i = 0;
        while ($i < 10) {
            $misc_cost = (new MiscCost())
                ->setType('testMiscellaneousCost' . $i)
                ->setValue(1253.50 + $i)
                ->setTimestamp(new \DateTime())
                ->setVehicle($this->getReference('test-vehicle-2' . $i))
                ->setCreator($this->getReference('test-user-1'));
            $manager->persist($misc_cost);
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
        return 6;
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