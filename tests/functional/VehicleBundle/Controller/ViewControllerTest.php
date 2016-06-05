<?php

namespace Tests\VehicleBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the vehicle view controller
 * src\VehicleBundle\Controller\ViewController
 */
class ViewControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\ViewController getAllAction get all vehicle owned and managed
     *
     * @dataProvider vehicleGetAllDataProvider
     *
     * @param boolean $user_logged_in
     * @param int $response_status
     * @param int $owned_vehicles_count
     * @param string $managed_vehicles_count
     */
    public function testVehicleGetAll($user_logged_in, $response_status, $owned_vehicles_count, $managed_vehicles_count) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/', array(), array(),
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
        if($owned_vehicles_count != null) {
            $this->assertEquals($owned_vehicles_count, sizeof($results->owned_vehicles));
        }
        if($managed_vehicles_count != null) {
            $this->assertEquals($managed_vehicles_count, sizeof($results->managed_vehicles));
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
     * For providing data for testing src\VehicleBundle\Controller\ViewController getAllAction getting all the vehicles owned and managed
     *
     * @return array
     */
    public function vehicleGetAllDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not retrieve the vehicles owned and managed
             * For when the user trying to get the vehicles is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, $constants->response->STATUS_USER_NOT_LOGGED_IN, null, null),
            /*
             * Should return all the vehicles owned and managed by the user
             * For when the request is a success
             *
             * The session should exist
             */
            'Success' => array(true, $constants->response->STATUS_SUCCESS, 10, 10),
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
     * @param string $response_status
     * @param $vehicle_returned
     */
    public function testVehicleGet($user_logged_in, $license_plate_no, $response_status, $vehicle_returned) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/', array(), array(),
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
        if($vehicle_returned) {
            $this->assertTrue(isset(json_decode($response->getContent())->vehicle));
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
             * For when the user trying to get the vehicles is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN00', $constants->response->STATUS_USER_NOT_LOGGED_IN, false),
            /*
             * Should not update the details of the vehicle
             * For when the user trying to get the vehicle is not a driver or owner of the vehicle
             *
             * The session does not exist
             */
            'UnownedAndUnManagedVehicle' => array(true, 'TEST-LPN20', $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER, false),
            /*
             * Should update the details of the vehicle
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN00', $constants->response->STATUS_SUCCESS, true),
        );
    }
}