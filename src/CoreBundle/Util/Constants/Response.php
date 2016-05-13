<?php

namespace CoreBundle\Util\Constants;

/*
 * For security related to responses to http requests
 */
class Response {
    public $STATUS = "status";
    public $VEHICLE = "vehicle";
    public $OWNED_VEHICLES = "owned_vehicles";
    public $MANAGED_VEHICLES = "managed_vehicles";

    /*
     * General
     */
    public $STATUS_SUCCESS = "SUCCESS";
    public $STATUS_NO_ARGUMENTS_PROVIDED = "NO_ARGUMENTS_PROVIDED";

    /*
     * User related
     */
    public $STATUS_USER_NOT_LOGGED_IN = "USER_NOT_LOGGED_IN";
    public $STATUS_USER_ALREADY_LOGGED_IN = "USER_ALREADY_LOGGED_IN";
    public $STATUS_USER_DUPLICATE_USERNAME = "USER_DUPLICATE_USERNAME";
    public $STATUS_USER_DUPLICATE_EMAIL = "USER_DUPLICATE_EMAIL";
    public $STATUS_USER_NOT_REGISTERED = "USER_NOT_REGISTERED";
    public $STATUS_USER_WRONG_PASSWORD = "USER_WRONG_PASSWORD";
    public $STATUS_USER_NOT_ACTIVE = "USER_NOT_ACTIVE";
    public $STATUS_USER_NOT_VERIFIED = "USER_NOT_VERIFIED";

    /*
     * Vehicle related
     */
    public $STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO = "VEHICLE_DUPLICATE_LICENSE_PLATE_NO";
    public $STATUS_VEHICLE_DOES_NOT_EXIST = "VEHICLE_DOES_NOT_EXIST";
    public $STATUS_VEHICLE_NOT_OWNED = "VEHICLE_NOT_OWNED";
    public $STATUS_VEHICLE_OWNER_CANNOT_BE_A_DRIVER = "VEHICLE_OWNER_CANNOT_BE_A_DRIVER";
    public $STATUS_VEHICLE_DUPLICATE_DRIVER = "VEHICLE_DUPLICATE_DRIVER";
    public $STATUS_VEHICLE_NOT_A_DRIVER = "VEHICLE_NOT_A_DRIVER";
}