<?php

namespace Tests\CoreBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

/*
 * Functional Tests
 *
 * For testing the authentication controller
 * src\CoreBundle\Util\AuthenticationController
 */
class AuthenticationControllerTest extends WebTestCase {
    /**
     * Functional Test
     *
     * Should create a new user and insert into the user table of the database
     * For when user trying to create the account is already logged in
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testSignUpForUserLoggedIn() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Creating a mock session
        $session = $client->getContainer()->get('session');
        $session->set($constants->session->USERNAME, 'testUser');

        // Requesting
        $client->request('POST', '/signUp', array(), array(),
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
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_ALREADY_LOGGED_IN, json_decode($response->getContent())->status);
        $this->assertNotNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should create a new user and insert into the user table of the database
     * For when user details are not provided
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testSignUpForUserDetailsNotGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/signUp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_NO_ARGUMENTS_PROVIDED, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should create a new user and insert into the user table of the database
     * For when user details are provided with duplicate email
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testSignUpForDuplicateEmailGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/signUp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testNewUser',
                'first_name' => 'testNewFirstName',
                'last_name' => 'testNewLastName',
                'password' => 'testNewPassword',
                'email' => 'testEmail@gmail.com',
            )))
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_DUPLICATE_EMAIL, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should create a new user and insert into the user table of the database
     * For when user details are provided with duplicate username
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testSignUpForDuplicateUsernameGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/signUp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUser',
                'first_name' => 'testNewFirstName',
                'last_name' => 'testNewLastName',
                'password' => 'testNewPassword',
                'email' => 'testNewEmail@gmail.com',
            )))
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_DUPLICATE_USERNAME, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should create a new user and insert into the user table of the database
     * For when user details are provided
     *
     * The session does not exist
     * The user does not exist in the database
     */
    public function testSignUpForUserDetailsGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/signUp', array(), array(),
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
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should set the session key username if username and password match
     * For when the username and password is not provided
     *
     * The user exists in the database
     */
    public function testLoginForUsernameAndPasswordNotGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_NO_ARGUMENTS_PROVIDED, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should set the session key username if username and password match
     * For when the username and password is provided for a non existing user
     *
     * The user exists in the database
     */
    public function testLoginForUsernameOfNonExistingUserGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testNonExistentUser',
                'password' => 'testNonExistentPassword',
            )))
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_NOT_REGISTERED, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should set the session key username if username and password match
     * For when the username is provided for a non existing user but password is wrong
     *
     * The user exists in the database
     */
    public function testLoginForUsernameAndWrongPasswordOfExistingUserGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUser',
                'password' => 'testWrongPassword',
            )))
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_WRONG_PASSWORD, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should set the session key username if username and password match
     * For when the username and password is provided for an existing inactive user
     *
     * The user exists in the database
     */
    public function testLoginForUsernameAndPasswordOfExistingInactiveUserGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testInactiveUser',
                'password' => 'testInactivePassword',
            )))
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_NOT_VERIFIED, json_decode($response->getContent())->status);
        $this->assertNull($client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should set the session key username if username and password match
     * For when the username and password is provided for an existing active user
     *
     * The user exists in the database
     */
    public function testLoginForUsernameAndPasswordOfExistingActiveUserGiven() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Requesting
        $client->request('POST', '/login', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('user' => array(
                'username' => 'testUser',
                'password' => 'testPassword',
            )))
        );
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertEquals('testUser', $client->getContainer()->get('session')->get($constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should destroy the session
     * For all scenarios
     *
     * The session is already set
     */
    public function testLogoutForAllScenarios() {
        // Creating a constant retriever
        $constants = new Retriever();

        // Creating a mock client
        $client = static::createClient();

        // Creating a mock session
        $session = $client->getContainer()->get('session');
        $session->set($constants->session->USERNAME, 'testUser');

        // Requesting
        $client->request('GET', '/logout');
        $response = $client->getResponse();

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertNull($session->get($constants->session->USERNAME));
    }
}