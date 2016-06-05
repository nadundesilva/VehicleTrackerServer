<?php

namespace Tests\CheckInBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the check in bundle manage controller
 * src\CheckInBundle\Controller\ManageController
 */
class CheckInControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\CheckInBundle\Controller\ViewController getAllAction get all check ins for a specific vehicle
     *
     * @dataProvider checkInGetAllDataProvider
     *
     * @param boolean $user_logged_in
     * @param $license_plate_no
     * @param int $response_status
     * @param $check_ins_count
     */
    public function testCheckInGetAll($user_logged_in, $license_plate_no, $response_status, $check_ins_count) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/check-in/', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            null
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $results = json_decode($response->getContent());
        $this->assertEquals($response_status, $results->status);
        if($check_ins_count != null) {
            $this->assertEquals($check_ins_count, sizeof($results->check_ins));
        }
        if($user_logged_in) {
            $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }

    }

    /**
     * Data Provider
     *
     * For providing data for testing src\CheckInBundle\Controller\ViewController getAllAction getting all the check ins for a specific vehicle
     *
     * @return array
     */
    public function checkInGetAllDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not retrieve the vehicles owned and managed
             * For when the user trying to get the check ins is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN10', $constants->response->STATUS_USER_NOT_LOGGED_IN, null),
            /*
             * Should not retrieve the vehicles owned and managed
             * For when the user trying to get the check ins does not own the vehicle and not a driver of it
             *
             * The session does not exist
             */
            'UnownedAndUnManagedVehicle' => array(true, 'TEST-LPN30', $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER, null),
            /*
             * Should return all the vehicles owned and managed by the user
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN10', $constants->response->STATUS_SUCCESS, 1),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\ManageController getAction getting a specific vehicle
     *
     * @dataProvider vehicleGetDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param $check_in_id
     * @param string $response_status
     * @param $check_in_returned
     */
    public function testVehicleGet($user_logged_in, $license_plate_no, $check_in_id, $response_status, $check_in_returned) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/check-in/' . $check_in_id . '/', array(), array(),
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
        if($check_in_returned) {
            $this->assertTrue(isset(json_decode($response->getContent())->check_in));
        }
        if($user_logged_in) {
            $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
        } else {
            $this->assertNull($this->session->get($this->constants->session->USERNAME));
        }
    }

    /**
     * Data Provider
     *
     * For providing data for testing src\VehicleBundle\Controller\ViewController getAction getting a specific vehicle
     *
     * @return array
     */
    public function vehicleGetDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not update the details of the vehicle
             * For when the user trying to get the check is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN10', 1, $constants->response->STATUS_USER_NOT_LOGGED_IN, false),
            /*
             * Should not update the details of the vehicle
             * For when the user trying to get the check in does not own the vehicle it is asigned to
             *
             * The session does not exist
             */
            'UnownedAndUnManagedVehicle' => array(true, 'TEST-LPN20', 21, $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER, false),
            /*
             * Should not update the details of the vehicle
             * For when the check in the user is trying to get does not exist
             *
             * The session does not exist
             */
            'CheckInDoesNotExist' => array(true, 'TEST-LPN10', 50, $constants->response->STATUS_CHECK_IN_DOES_NOT_EXIST, false),
            /*
             * Should update the details of the vehicle
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN10', 1, $constants->response->STATUS_SUCCESS, true),
        );
    }
}