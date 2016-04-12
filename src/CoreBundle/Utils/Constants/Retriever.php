<?php

namespace CoreBundle\Utils\Constants;

class Retriever {
    public $session;
    public $database;
    public $response;
    public $request;

    public function __construct() {
        $this->session = new Session();
        $this->database = new Database();
        $this->response = new Response();
        $this->request = new Request();
    }
}
