<?php

namespace Tests\FuelFillUpBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the check in bundle manage controller
 * src\CheckInBundle\Controller\ManageController
 */
class ViewControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\FuelFillUpBundle\Controller\ViewController getAllAction get all fuel fill ups for a specific vehicle
     *
     * @dataProvider fuelFillUpGetAllDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param int $response_status
     * @param int $fuel_fill_ups_count
     */
    public function testFuelFillUpGetAll($user_logged_in, $license_plate_no, $response_status, $fuel_fill_ups_count) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/fuel-fill-up/', array(), array(),
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
        if($fuel_fill_ups_count != null) {
            $this->assertEquals($fuel_fill_ups_count, sizeof($results->fuel_fill_ups));
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
     * For providing data for testing src\FuelFillUpBundle\Controller\ViewController getAllAction getting all the fuel fill ups for a specific vehicle
     *
     * @return array
     */
    public function fuelFillUpGetAllDataProvider() {
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
     * For testing src\FuelFillUpBundle\Controller\ManageController getAction getting a specific vehicle
     *
     * @dataProvider fuelFillUpGetDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $fuel_fill_up_id
     * @param string $response_status
     * @param bolean $fuel_fill_up_returned
     */
    public function testVehicleGet($user_logged_in, $license_plate_no, $fuel_fill_up_id, $response_status, $fuel_fill_up_returned) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/fuel-fill-up/' . $fuel_fill_up_id . '/', array(), array(),
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
        if($fuel_fill_up_returned) {
            $this->assertTrue(isset(json_decode($response->getContent())->fuel_fill_up));
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
    public function fuelFillUpGetDataProvider() {
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
            'FuelFillUpDoesNotExist' => array(true, 'TEST-LPN10', 50, $constants->response->STATUS_FUEL_FILL_UP_DOES_NOT_EXIST, false),
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