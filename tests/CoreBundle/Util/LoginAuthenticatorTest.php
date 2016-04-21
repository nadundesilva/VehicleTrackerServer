<?php

namespace Tests\CoreBundle\Util;

use CoreBundle\Entity\User;
use CoreBundle\Util\LoginAuthenticator;
use Doctrine\ORM\EntityRepository;
use Tests\BaseUnitTest;

/*
 * Unit Tests
 *
 * For testing the core login authenticator
 * src\CoreBundle\Util\LoginAuthenticator
 */
class LoginAuthenticatorTest extends BaseUnitTest {
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
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        $this->assertNull($user);
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
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testNonExistentUser');

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
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        $this->assertNull($user);
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
            ->will($this->returnValue($user));

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        $this->assertNull($user);
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
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser');

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
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        $this->assertNull($user);
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
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        $this->assertNull($user);
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
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser');

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
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        $this->assertInstanceOf('CoreBundle\Entity\User', $user);
        $this->assertNotNull($user);
    }
}