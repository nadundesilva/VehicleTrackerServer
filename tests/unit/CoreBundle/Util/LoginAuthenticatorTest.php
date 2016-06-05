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
     * For testing src\CoreBundle\Util\LoginAuthenticator authenticateUser
     *
     * @dataProvider authenticateUserDataProvider
     *
     * @param boolean $user_logged_in
     * @param boolean $user_get_active_called
     * @param boolean $user_active
     * @param boolean $user_find_called
     * @param boolean $user_found
     */
    public function testAuthenticateUser($user_logged_in, $user_get_active_called, $user_active, $user_find_called, $user_found) {
        if($user_logged_in != null) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser');
        }

        // Creating a mock user
        $user = $this->getMock(User::class);
        if($user_get_active_called) {
            $user->expects($this->once())
                ->method('getActive')
                ->will($this->returnValue($user_active));
        } else {
            $user->expects($this->never())
                ->method('getActive')
                ->will($this->returnValue($user_active));
        }

        // Creating a mock user repository
        $user_repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        if($user_find_called) {
            $user_repository->expects($this->once())
                ->method('find')
                ->will($this->returnValue($user));
        } else {
            $user_repository->expects($this->never())
                ->method('find')
                ->will($this->returnValue($user));
        }

        // Running the tested method
        /** @var EntityRepository $user_repository */
        $login_authenticator = new LoginAuthenticator($this->session, $user_repository, $this->constants);
        $user = $login_authenticator->authenticateUser();

        // Assertions
        if($user_found) {
            $this->assertInstanceOf('CoreBundle\Entity\User', $user);
        } else {
            $this->assertNull($user);
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\CoreBundle\Util\LoginAuthenticator authenticateUser
     *
     * @return array
     */
    public function authenticateUserDataProvider() {
        return array(
            /*
             * Should not call get active
             * Should not call find user
             * For users who had not signed up in the system yet
             *
             * The session exists
             * The user is not in the database
             */
            'NonExistingUser' => array(false, false, false, false, false),
            /*
             * Should not call get active
             * Should call find user once and it should return null
             * For users who had logged in but had been removed from the database
             *
             * The session exists
             * The user is not in the database
             */
            'LoggedInNonExistingUser' => array(true, true, false, true, false),
            /*
             * Should not call get active once and it should return false
             * Should not call find user
             * For users who had signed up but had not verified their account
             *
             * The session does not exist
             * The user is in the database but active is set to false
             */
            'NonActiveExistingUser' => array(false, false, false, false, false),
            /*
             * Should call get active once and it should return false
             * Should call find user once and it should return the correct user
             * For users who had logged in but the account had been deactivated
             *
             * The session exists
             * The user is in the database but active is set to false
             */
            'LoggedInNonActiveExistingUser' => array(true, true, false, true, false),
            /*
             * Should not call get active
             * Should not call find user
             * For users who had not logged in but the account had been created and verified
             *
             * The session does not exist
             * The user is in the database and active is set to true
             */
            'NotLoggedInActiveExistingUser' => array(false, false, true, false, false),
            /*
             * Should call get active once and it should return true
             * Should call find user once and it should return the correct user
             * For users who had logged in with an existing verified account
             *
             * The session exists
             * The user is in the database and active is set to true
             */
            'LoggedInActiveExistingUser' => array(true, true, true, true, true),
        );
    }
}