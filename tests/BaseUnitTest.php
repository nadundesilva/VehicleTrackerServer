<?php

namespace Tests;

use CoreBundle\Util\Constants\Retriever;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/*
 * Base Unit Test
 *
 * All the other unit testing classes inherits from this class
 */
abstract class BaseUnitTest extends \PHPUnit_Framework_TestCase {
    /** @var Retriever $constants */
    protected $constants;
    /** @var Session $session */
    protected $session;

    /**
     * Sets up the class attributes for testing
     */
    public function setUp() {
        parent::setUp();

        // Creating a constant retriever
        $this->constants = new Retriever();

        // Getting the session of the client
        $this->session = new Session(new MockArraySessionStorage());
    }
}