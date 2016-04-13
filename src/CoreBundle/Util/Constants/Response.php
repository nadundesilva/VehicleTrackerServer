<?php

namespace CoreBundle\Util\Constants;

/*
 * For security related to responses to http requests
 */
class Response {
    public $STATUS = "status";

    public $STATUS_SUCCESS = "SUCCESS";
    public $STATUS_NO_ARGUMENTS_PROVIDED = "NO_ARGUMENTS_PROVIDED";
    public $STATUS_NOT_LOGGED_IN = "NOT_LOGGED_IN";
    public $STATUS_ALREADY_LOGGED_IN = "ALREADY_LOGGED_IN";

    public $STATUS_DUPLICATE_USERNAME = "DUPLICATE_USERNAME";
    public $STATUS_DUPLICATE_EMAIL = "DUPLICATE_EMAIL";

    public $STATUS_NOT_REGISTERED = "NOT_REGISTERED";
    public $STATUS_WRONG_PASSWORD = "WRONG_PASSWORD";
    public $STATUS_NOT_VERIFIED = "NOT_VERIFIED";
}