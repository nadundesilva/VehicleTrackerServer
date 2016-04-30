<?php

namespace Tests\CoreBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the core authentication controller
 * src\CoreBundle\Controller\AuthenticationController
 */
class AuthenticationFunctionalTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\CoreBundle\Controller\AuthenticationController signUpAction
     *
     * @dataProvider userSignUpDataProvider
     *
     * @param $logged_in_username
     * @param string $username
     * @param string $first_name
     * @param string $last_name
     * @param string $password
     * @param string $email
     * @param string $response_status
     * @param boolean $session_not_null
     */
    public function testUserSignUp($logged_in_username, $username, $first_name, $last_name, $password, $email, $response_status, $session_not_null) {
        if($logged_in_username != null) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, $logged_in_username);
        }

        // Creating request body
        $user = array();
        if($username != null) {
            $user['username'] = $username;
        }
        if($first_name != null) {
            $user['first_name'] = $first_name;
        }
        if($last_name != null) {
            $user['last_name'] = $last_name;
        }
        if($password != null) {
            $user['password'] = $password;
        }
        if($email != null) {
            $user['email'] = $email;
        }
        if(sizeof($user) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('user' => $user));
        }

        // Requesting
        $this->client->request('POST', '/sign-up', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            $content
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($response_status, json_decode($response->getContent())->status);
        if($session_not_null) {
            $this->assertNotNull($this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\CoreBundle\Controller\AuthenticationController signUpAction
     *
     * @return array
     */
    public function userSignUpDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not create a new user in the user table of the database
             * Should not set the session
             * For when the user trying to create the account is already logged in
             *
             * The session does not exist
             * The user does not exist in the database
             */
            'UserLoggedIn' => array('testUser0', 'testNewUser', 'testNewFirstName', 'testNewLastName', 'testNewPassword', 'testNewEmail@gmail.com', $constants->response->STATUS_USER_ALREADY_LOGGED_IN, true),
            /*
             * Should not create a new user in the user table of the database
             * Should not set the session
             * For when user details are not provided
             *
             * The session does not exist
             * The user does not exist in the database
             */
            'DetailsNotGiven' => array(null, null, null, null, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED, false),
            /*
             * Should not create a new user in the user table of the database
             * Should not set the session
             * For when user details are provided with duplicate email
             *
             * The session does not exist
             * The user does not exist in the database
             */
            'DuplicateEmailGiven' => array(null, 'testNewUser', 'testNewFirstName', 'testNewLastName', 'testNewPassword', 'testEmail0@gmail.com', $constants->response->STATUS_USER_DUPLICATE_EMAIL, false),
            /*
             * Should not create a new user in the user table of the database
             * Should not set the session
             * For when user details are provided with duplicate username
             *
             * The session does not exist
             * The user does not exist in the database
             */
            'DuplicateUsernameGiven' => array(null, 'testUser0', 'testNewFirstName', 'testNewLastName', 'testNewPassword', 'testNewEmail@gmail.com', $constants->response->STATUS_USER_DUPLICATE_USERNAME, false),
            /*
             * Should create a new user in the user table of the database
             * Should set the session
             * For when user details are provided
             *
             * The session does not exist
             * The user does not exist in the database
             */
            'DetailsGiven' => array(null, 'testNewUser', 'testNewFirstName', 'testNewLastName', 'testNewPassword', 'testNewEmail@gmail.com', $constants->response->STATUS_SUCCESS, false)
        );
    }

    /**
     * Functional Test
     *
     * For testing src\CoreBundle\Controller\AuthenticationController loginAction
     *
     * @dataProvider userLoginDataProvider
     *
     * @param string $username
     * @param string $password
     * @param string $response_status
     * @param boolean $session_not_null
     */
    public function testUserLogin($username, $password, $response_status, $session_not_null) {
        // Creating request body
        $user = array();
        if($username != null) {
            $user['username'] = $username;
        }
        if($password != null) {
            $user['password'] = $password;
        }
        if(sizeof($user) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('user' => $user));
        }

        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            $content
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($response_status, json_decode($response->getContent())->status);
        if($session_not_null) {
            $this->assertEquals($username, $this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\CoreBundle\Controller\AuthenticationController loginAction
     *
     * @return array
     */
    public function userLoginDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not set the session
             * For when the username and password is not provided
             *
             * The user exists in the database
             */
            'UsernameAndPasswordNotGiven' => array(null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED, false),
            /*
             * Should not set the session
             * For when the username and password is provided for a non existing user
             *
             * The user exists in the database
             */
            'UsernameOfNonExistingUserGiven' => array('testNonExistentUser', 'testNonExistentPassword', $constants->response->STATUS_USER_NOT_REGISTERED, false),
            /*
             * Should not set the session
             * For when the username and password is provided for an existing inactive user
             *
             * The user exists in the database
             */
            'UsernameAndWrongPasswordOfExistingUserGiven' => array('testUser0', 'testWrongPassword', $constants->response->STATUS_USER_WRONG_PASSWORD, false),
            /*
             * Should not set the session
             * For when the username and password is provided for an existing inactive user
             *
             * The user exists in the database
             */
            'UsernameAndPasswordOfExistingInactiveUserGiven' => array('testInactiveUser', 'testInactivePassword', $constants->response->STATUS_USER_NOT_ACTIVE, false),
            /*
             * Should not set the session
             * For when the username and password is provided for an existing unverified user
             *
             * The user exists in the database
             */
            'UsernameAndPasswordOfExistingUnverifiedUserGiven' => array('testUnverifiedUser', 'testUnverifiedPassword', $constants->response->STATUS_USER_NOT_VERIFIED, false),
            /*
             * Should set the session key username
             * For when the username and password is provided for an existing active user
             *
             * The user exists in the database
             */
            'UsernameAndPasswordOfExistingActiveUserGiven' => array('testUser0', 'testPassword0', $constants->response->STATUS_SUCCESS, true),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\CoreBundle\Controller\AuthenticationController logoutAction
     *
     * @dataProvider userLogoutDataProvider
     *
     * @param $logged_in_username
     */
    public function testUserLogout($logged_in_username) {
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, $logged_in_username);

        // Requesting
        $this->client->request('GET', '/logout');
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\CoreBundle\Controller\AuthenticationController logoutAction
     *
     * @return array
     */
    public function userLogoutDataProvider() {
        return array(
            /*
             * Should destroy the session
             * For all scenarios
             *
             * The session is already set
             */
            'UsernameAndPasswordNotGiven' => array('testUser0'),
        );
    }
}