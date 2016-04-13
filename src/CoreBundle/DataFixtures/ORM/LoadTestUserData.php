<?php

namespace CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * For loading user entries to the test database
 *
 * Only used in test environment
 */
class LoadTestUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Loads the user objects for testing
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        {
            $testUser = new User();
            $testUser->setUsername('testUser');
            $testUser->setFirstName('testFirstName');
            $testUser->setLastName('testLastName');
            $testUser->setPassword(password_hash('testPassword', PASSWORD_DEFAULT));
            $testUser->setEmail('testEmail@gmail.com');
            $testUser->setActive(true);
            $testUser->setLastLoginTime(new \DateTime());
            $manager->persist($testUser);
            $manager->flush();
            $this->addReference('test-user', $testUser);
        }
        {
            $testUser = new User();
            $testUser->setUsername('testInactiveUser');
            $testUser->setFirstName('testInactiveFirstName');
            $testUser->setLastName('testInactiveLastName');
            $testUser->setPassword(password_hash('testInactivePassword', PASSWORD_DEFAULT));
            $testUser->setEmail('testInactiveEmail@gmail.com');
            $testUser->setActive(false);
            $testUser->setLastLoginTime(new \DateTime());
            $manager->persist($testUser);
            $manager->flush();
            $this->addReference('test-inactive-user', $testUser);
        }
    }

    /**
     * Returns the order of persisting user entities
     *
     * @return int
     */
    public function getOrder() {
        return 1;
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