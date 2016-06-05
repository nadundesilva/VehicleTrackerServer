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
    public $DRIVERS = "drivers";
    public $CHECK_IN = "check_in";
    public $CHECK_INS = "check_ins";
    public $FUEL_FILL_UP = "fuel_fill_up";
    public $FUEL_FILL_UPS = "fuel_fill_ups";
    public $MISC_COST = "misc_cost";
    public $MISC_COSTS = "misc_costs";

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
    public $STATUS_VEHICLE_OWNER_CANNOT_BE_DRIVER = "VEHICLE_OWNER_CANNOT_BE_DRIVER";
    public $STATUS_VEHICLE_DUPLICATE_DRIVER = "VEHICLE_DUPLICATE_DRIVER";
    public $STATUS_VEHICLE_NOT_DRIVER = "VEHICLE_NOT_DRIVER";
    public $STATUS_VEHICLE_NOT_DRIVER_OR_OWNER = "VEHICLE_NOT_DRIVER_OR_OWNER";

    /*
     * Check in related
     */
    public $STATUS_CHECK_IN_DOES_NOT_EXIST = "CHECK_IN_DOES_NOT_EXIST";
    public $STATUS_CHECK_IN_NOT_CREATOR = "CHECK_IN_NOT_CREATOR";
    public $STATUS_CHECK_IN_NOT_ASSIGNED_TO_VEHICLE = "CHECK_IN_NOT_ASSIGNED_TO_VEHICLE";

    /*
     * Fuel fill up related
     */
    public $STATUS_FUEL_FILL_UP_DOES_NOT_EXIST = "FUEL_FILL_UP_DOES_NOT_EXIST";
    public $STATUS_FUEL_FILL_UP_NOT_CREATOR = "FUEL_FILL_UP_NOT_CREATOR";
    public $STATUS_FUEL_FILL_UP_NOT_ASSIGNED_TO_VEHICLE = "FUEL_FILL_UP_NOT_ASSIGNED_TO_VEHICLE";

    /*
     * Misc cost related
     */
    public $STATUS_MISC_COST_DOES_NOT_EXIST = "MISC_COST_DOES_NOT_EXIST";
    public $STATUS_MISC_COST_NOT_CREATOR = "MISC_COST_NOT_CREATOR";
    public $STATUS_MISC_COST_NOT_ASSIGNED_TO_VEHICLE = "MISC_COST_NOT_ASSIGNED_TO_VEHICLE";
}
