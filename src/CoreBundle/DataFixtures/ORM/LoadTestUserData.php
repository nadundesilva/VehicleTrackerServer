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
        // Creating general users
        $i = 0;
        while ($i < 10) {
            $testUser = (new User())
                ->setUsername('testUser' . $i)
                ->setFirstName('testFirstName' . $i)
                ->setLastName('testLastName' . $i)
                ->setPassword(password_hash('testPassword' . $i, PASSWORD_DEFAULT))
                ->setEmail('testEmail' . $i . '@gmail.com')
                ->setVerified(true)
                ->setActive(true)
                ->setLastLoginTime(new \DateTime());
            $manager->persist($testUser);
            $manager->flush();
            $this->addReference('test-user-' . $i, $testUser);
            $i++;
        }

        // Creating inactive user
        $testInactiveUser = (new User())
            ->setUsername('testInactiveUser')
            ->setFirstName('testInactiveFirstName')
            ->setLastName('testInactiveLastName')
            ->setPassword(password_hash('testInactivePassword', PASSWORD_DEFAULT))
            ->setEmail('testInactiveEmail@gmail.com')
            ->setVerified(true)
            ->setActive(false)
            ->setLastLoginTime(new \DateTime());
        $manager->persist($testInactiveUser);
        $manager->flush();

        // Creating enverified user
        $testUnverifiedUser = (new User())
            ->setUsername('testUnverifiedUser')
            ->setFirstName('testUnverifiedFirstName')
            ->setLastName('testUnverifiedLastName')
            ->setPassword(password_hash('testUnverifiedPassword', PASSWORD_DEFAULT))
            ->setEmail('testUnverifiedEmail@gmail.com')
            ->setVerified(false)
            ->setActive(true)
            ->setLastLoginTime(new \DateTime());
        $manager->persist($testUnverifiedUser);
        $manager->flush();
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