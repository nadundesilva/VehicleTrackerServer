<?php

namespace Tests\CoreBundle\Util;

use CoreBundle\Entity\User;
use CoreBundle\Util\Constants\Retriever;
use CoreBundle\Util\LoginAuthenticator;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/*
 * Unit Tests
 *
 * For testing the login authenticator
 * src\CoreBundle\Util\LoginAuthenticator
 */
class LoginAuthenticatorTest extends \PHPUnit_Framework_TestCase {
    /**
     * Unit Test
     *
     * Should assert to false
     * For users who had not signed up in the system yet
     *
     * The session exists
     * The user is not in the database
     */
    public function testAuthenticateUserForNonExistingUser() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock session
        $session = new Session(new MockArraySessionStorage());

        // Creating a mock user
        $user = $this->getMock(User::class);
        $user->expects($this->never())
            ->method('getActive')
            ->will($this->returnValue(false));

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user_repository->expects($this->never())
            ->method('find')
            ->will($this->returnValue(null));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($session, $user_repository, $constants);

        // Assertions
        $this->assertNotTrue($login_authenticator->authenticateUser());
    }
    
    /**
     * Unit Test
     *
     * Should assert to false
     * For users who had logged in but had been removed from the database
     *
     * The session exists
     * The user is not in the database
     */
    public function testAuthenticateUserForLoggedInNonExistingUser() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock session
        $session = new Session(new MockArraySessionStorage());
        $session->set($constants->session->USERNAME, 'testNonExistentUser');

        // Creating a mock user
        $user = $this->getMock(User::class);
        $user->expects($this->never())
            ->method('getActive')
            ->will($this->returnValue(false));

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user_repository->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($session, $user_repository, $constants);

        // Assertions
        $this->assertNotTrue($login_authenticator->authenticateUser());
    }
    
    /**
     * Unit Test
     *
     * Should assert to false
     * For users who had signed up but had not verified their account
     *
     * The session does not exist
     * The user is in the database but active is set to false
     */
    public function testAuthenticateUserForNonActiveExistingUser() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock session
        $session = new Session(new MockArraySessionStorage());
        $session->set($constants->session->USERNAME, 'testUser');

        // Creating a mock user
        $user = $this->getMock(User::class);
        $user->expects($this->once())
            ->method('getActive')
            ->will($this->returnValue(false));

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user_repository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($user));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($session, $user_repository, $constants);

        // Assertions
        $this->assertNotTrue($login_authenticator->authenticateUser());
    }

    /**
     * Unit Test
     *
     * Should assert to false
     * For users who had logged in but the account had been deactivated
     *
     * The session exists
     * The user is in the database but active is set to false
     */
    public function testAuthenticateUserForLoggedInNonActiveExistingUser() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock session
        $session = new Session(new MockArraySessionStorage());
        $session->set($constants->session->USERNAME, 'testUser');

        // Creating a mock user
        $user = $this->getMock(User::class);
        $user->expects($this->once())
            ->method('getActive')
            ->will($this->returnValue(false));

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user_repository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($user));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($session, $user_repository, $constants);

        // Assertions
        $this->assertNotTrue($login_authenticator->authenticateUser());
    }

    /**
     * Unit Test
     *
     * Should assert to false
     * For users who had not logged in but the account had been created and verified
     *
     * The session does not exist
     * The user is in the database and active is set to true
     */
    public function testAuthenticateUserForNotLoggedInActiveExistingUser() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock session
        $session = new Session(new MockArraySessionStorage());

        // Creating a mock user
        $user = $this->getMock(User::class);
        $user->expects($this->never())
            ->method('getActive')
            ->will($this->returnValue(true));

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user_repository->expects($this->never())
            ->method('find')
            ->will($this->returnValue($user));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($session, $user_repository, $constants);

        // Assertions
        $this->assertNotTrue($login_authenticator->authenticateUser());
    }

    /**
     * Unit Test
     *
     * Should assert to true
     * For users who had not logged in but the account had been created and verified
     *
     * The session does not exist
     * The user is in the database and active is set to true
     */
    public function testAuthenticateUserForLoggedInActiveExistingUser() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock session
        $session = new Session(new MockArraySessionStorage());
        $session->set($constants->session->USERNAME, 'testUser');

        // Creating a mock user
        $user = $this->getMock(User::class);
        $user->expects($this->once())
            ->method('getActive')
            ->will($this->returnValue(true));

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user_repository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($user));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($session, $user_repository, $constants);

        // Assertions
        $this->assertTrue($login_authenticator->authenticateUser());
    }
}