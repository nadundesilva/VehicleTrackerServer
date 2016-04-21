<?php

namespace Tests\CoreBundle\Controller;

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
     * Should not create a new user in the user table of the database
     * Should not set the session
     * For when the user trying to create the account is already logged in
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testUserSignUpForUserLoggedIn() {
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser0');

        // Requesting
        $this->client->request('POST', '/sign-up', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testNewUser',
                'first_name' => 'testNewFirstName',
                'last_name' => 'testNewLastName',
                'password' => 'testNewPassword',
                'email' => 'testNewEmail@gmail.com',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_ALREADY_LOGGED_IN, json_decode($response->getContent())->status);
        $this->assertNotNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not create a new user in the user table of the database
     * Should not set the session
     * For when user details are not provided
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testUserSignUpForDetailsNotGiven() {
        // Requesting
        $this->client->request('POST', '/sign-up', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_NO_ARGUMENTS_PROVIDED, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not create a new user in the user table of the database
     * Should not set the session
     * For when user details are provided with duplicate email
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testUserSignUpForDuplicateEmailGiven() {
        // Requesting
        $this->client->request('POST', '/sign-up', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testNewUser',
                'first_name' => 'testNewFirstName',
                'last_name' => 'testNewLastName',
                'password' => 'testNewPassword',
                'email' => 'testEmail0@gmail.com',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_DUPLICATE_EMAIL, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not create a new user in the user table of the database
     * Should not set the session
     * For when user details are provided with duplicate username
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testUserSignUpForDuplicateUsernameGiven() {
        // Requesting
        $this->client->request('POST', '/sign-up', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUser0',
                'first_name' => 'testNewFirstName',
                'last_name' => 'testNewLastName',
                'password' => 'testNewPassword',
                'email' => 'testNewEmail@gmail.com',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_DUPLICATE_USERNAME, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should create a new user in the user table of the database
     * Should set the session
     * For when user details are provided
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testUserSignUpForDetailsGiven() {
        // Requesting
        $this->client->request('POST', '/sign-up', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testNewUser',
                'first_name' => 'testNewFirstName',
                'last_name' => 'testNewLastName',
                'password' => 'testNewPassword',
                'email' => 'testNewEmail@gmail.com',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not set the session
     * For when the username and password is not provided
     *
     * The user exists in the database
     */
    public function testUserLoginForUsernameAndPasswordNotGiven() {
        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_NO_ARGUMENTS_PROVIDED, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not set the session
     * For when the username and password is provided for a non existing user
     *
     * The user exists in the database
     */
    public function testUserLoginForUsernameOfNonExistingUserGiven() {
        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testNonExistentUser',
                'password' => 'testNonExistentPassword',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_NOT_REGISTERED, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not set the session
     * For when the username is provided for a non existing user but password is wrong
     *
     * The user exists in the database
     */
    public function testUserLoginForUsernameAndWrongPasswordOfExistingUserGiven() {
        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUser0',
                'password' => 'testWrongPassword',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_WRONG_PASSWORD, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not set the session
     * For when the username and password is provided for an existing inactive user
     *
     * The user exists in the database
     */
    public function testUserLoginForUsernameAndPasswordOfExistingInactiveUserGiven() {
        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testInactiveUser',
                'password' => 'testInactivePassword',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_NOT_ACTIVE, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not set the session
     * For when the username and password is provided for an existing unverified user
     *
     * The user exists in the database
     */
    public function testUserLoginForUsernameAndPasswordOfExistingUnverifiedUserGiven() {
        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUnverifiedUser',
                'password' => 'testUnverifiedPassword',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_NOT_VERIFIED, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should set the session key username
     * For when the username and password is provided for an existing active user
     *
     * The user exists in the database
     */
    public function testUserLoginForUsernameAndPasswordOfExistingActiveUserGiven() {
        // Requesting
        $this->client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUser0',
                'password' => 'testPassword0',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should destroy the session
     * For all scenarios
     *
     * The session is already set
     */
    public function testUserLogoutForAllScenarios() {
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser0');

        // Requesting
        $this->client->request('GET', '/logout');
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }
}