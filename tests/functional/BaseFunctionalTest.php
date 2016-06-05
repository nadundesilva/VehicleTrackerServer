<?php

namespace Tests;

use CoreBundle\Util\Constants\Retriever;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Client;

/*
 * Base Functional Test
 *
 * All the other controller testing classes inherits from this class
 */
abstract class BaseFunctionalTest extends WebTestCase {
    /** @var Retriever $constants */
    protected $constants;
    /** @var Client $client */
    protected $client;
    /** @var Session $session */
    protected $session;

    /**
     * Sets up the class attributes for testing
     */
    public function setUp() {
        parent::setUp();

        // Creating a constant retriever
        $this->constants = new Retriever();

        // Creating a mock client
        $this->client = static::createClient();

        // Getting the session of the client
        $this->session = $this->client->getContainer()->get('session');
    }

    /**
     * Asserts if the passed response is successful
     *
     * @param Response $response
     */
    public function assertSuccessfulResponse(Response $response) {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('Content-Type', 'application/json'));
    }
}