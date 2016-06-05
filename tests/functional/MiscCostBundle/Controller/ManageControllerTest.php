<?php

namespace Tests\MiscCostBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the check in bundle manage controller
 * src\CheckInBundle\Controller\ManageController
 */
class ManageControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\MiscCostBundle\Controller\ManageController createAction creating miscellaneous cost
     *
     * @dataProvider vehicleMiscCostCreateDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $type
     * @param float $value
     * @param string $response_status
     */
    public function testVehicleMiscCostCreate($user_logged_in, $license_plate_no, $type, $value, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $misc_cost = array();
        if($type != null) {
            $misc_cost['type'] = $type;
        }
        if($value != null) {
            $misc_cost['value'] = $value;
        }
        if(sizeof($misc_cost) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('misc_cost' => $misc_cost));
        }

        // Requesting
        $this->client->request('POST', '/vehicle/' . $license_plate_no . '/misc-cost/' , array(), array(),
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
        if($user_logged_in) {
            $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\MiscCostBundle\Controller\ManageController createAction creating miscellaneous cost
     *
     * @return array
     */
    public function vehicleMiscCostCreateDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not create a new check in the check in table of the database
             * For when the user trying to create the check in is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN10', 'testMiscellaneousCost', 1253.50, $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not create a new check in the check in table of the database
             * For when the details of the vehicle to be created is not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array(true, 'TEST-LPN10', null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
            /*
             * Should not create a new check in the check in table of the database
             * For when the vehicle the check in is requested to be created for does not exist
             *
             * The session should exist
             */
            'VehicleDoesNotExist' => array(true, 'TEST-NELPN', 'testMiscellaneousCost', 1253.50, $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not create a new check in the check in table of the database
             * For when the vehicle the check in is requested to be created for is not owned by the user or the user is not a driver of it
             *
             * The session should exist
             */
            'UserNotADriverOrOwner' => array(true, 'TEST-LPN20', 'testMiscellaneousCost', 1253.50, $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER),
            /*
             * Should create a new check in the check in table of the database
             * For when the check in details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN10', 'testMiscellaneousCost', 1253.50, $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\MiscCostBundle\Controller\ManageController updateAction updating miscellaneous cost
     *
     * @dataProvider vehicleMiscCostUpdateDataProvider
     *
     * @param string $license_plate_no
     * @param int $misc_cost_id
     * @param boolean $user_logged_in
     * @param string $type
     * @param float $value
     * @param string $response_status
     */
    public function testVehicleMiscCostUpdate($license_plate_no, $misc_cost_id, $user_logged_in, $type, $value, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $misc_cost = array();
        if($type != null) {
            $misc_cost['type'] = $type;
        }
        if($value != null) {
            $misc_cost['value'] = $value;
        }
        if(sizeof($misc_cost) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('misc_cost' => $misc_cost));
        }

        // Requesting
        $this->client->request('PUT', '/vehicle/' . $license_plate_no . '/misc-cost/' . $misc_cost_id . '/', array(), array(),
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
        if($user_logged_in) {
            $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\MiscCostBundle\Controller\ManageController updateAction updating miscellaneous cost
     *
     * @return array
     */
    public function vehicleMiscCostUpdateDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not update the details of the check in
             * For when the user trying to update the check in is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array('TEST-LPN10', 31, false, 'newTestMiscellaneousCost', 1253.50, $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not update the details of the check in
             * For when the details of the check in to be updated are not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array('TEST-LPN10', 31, true, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
            /*
             * Should not update the details of the check in
             * For when the vehicle the check in is requested to be updated for does not exist
             *
             * The session should exist
             */
            'NonExistentVehicle' => array('TEST-NELPN', 31, true, 'newTestMiscellaneousCost', 1253.50, $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not update the details of the check in
             * For when the vehicle the check in is requested to be updated for is not owned by the user or the user is not a driver of it
             *
             * The session should exist
             */
            'UserNotADriverOrOwner' => array('TEST-LPN20', 31, true, 'newTestMiscellaneousCost', 1253.50, $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER),
            /*
             * Should not update the details of the check in
             * For when the check in to be updated does not exist
             *
             * The session should exist
             */
            'MiscCostDoesNotExist' => array('TEST-LPN10', 32, true, 'newTestMiscellaneousCost', 1253.50, $constants->response->STATUS_MISC_COST_DOES_NOT_EXIST),
            /*
             * Should not update the details of the check in
             * For when the check in to be updated does not exist
             *
             * The session should exist
             */
            'MiscCostNotCreator' => array('TEST-LPN10', 30, true, 'newTestMiscellaneousCost', 1253.50, $constants->response->STATUS_MISC_COST_NOT_CREATOR),
            /*
             * Should update the details of the check in
             * For when the vehicle details are3 provided
             *
             * The session should exist
             */
            'Success' => array('TEST-LPN10', 31, true, 'newTestMiscellaneousCost', 1253.50, $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\MiscCostBundle\Controller\ManageController removeAction removing miscellaneous cost
     *
     * @dataProvider vehicleMiscCostRemoveDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param int $misc_cost_id
     * @param string $response_status
     */
    public function testVehicleMiscCostRemove($user_logged_in, $license_plate_no, $misc_cost_id, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('DELETE', '/vehicle/' . $license_plate_no  . '/misc-cost/' . $misc_cost_id . '/', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            null
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($response_status, json_decode($response->getContent())->status);
        if($user_logged_in) {
            $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\MiscCostBundle\Controller\ManageController deleteAction deleting vehicle
     *
     * @return array
     */
    public function vehicleMiscCostRemoveDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not delete the vehicle
             * For when the user trying to delete the vehicle is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN10', 31, $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not delete the vehicle
             * For when the check in to be deleted does not exist
             *
             * The session should exist
             */
            'NonExistentMiscCost' => array(true, 'TEST-LPN10', 32, $constants->response->STATUS_MISC_COST_DOES_NOT_EXIST),
            /*
             * Should not delete the vehicle
             * For when the user is not the owner or a driver of the vehicle to which the check in to be removed assigned
             *
             * The session should exist
             */
            'UserNotDriverOrOwner' => array(true, 'TEST-LPN20', 21, $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER),
            /*
             * Should not delete the vehicle
             * For when the check in to be removed is not assigned to the vehicle specified
             *
             * The session should exist
             */
            'MiscCostNotAssignedToSpecifiedVehicle' => array(true, 'TEST-LPN11', 31, $constants->response->STATUS_MISC_COST_NOT_ASSIGNED_TO_VEHICLE),
            /*
             * Should not delete the vehicle
             * For when the user trying to remove the check in is not the creator of the check in
             *
             * The session should exist
             */
            'UserNotCreatorOfMiscCost' => array(true, 'TEST-LPN10', 11, $constants->response->STATUS_MISC_COST_NOT_CREATOR),
            /*
             * Should delete the vehicle
             * For when the vehicle license plate no is provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN10', 31, $constants->response->STATUS_SUCCESS),
        );
    }
}