<?php

namespace CoreBundle\Util\Constants;

/*
 * For retrieving constants
 */
class Retriever {
    public $session;
    public $database;
    public $response;
    public $security;

    /**
     * Retriever constructor
     *
     * Used for retrieving constants
     */
    public function __construct() {
        $this->session = new Session();
        $this->database = new Database();
        $this->response = new Response();
        $this->security = new Security();
    }
}
