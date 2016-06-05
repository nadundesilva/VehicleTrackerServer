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
class ManageControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\FuelFillUpBundle\Controller\ManageController createAction creating check in
     *
     * @dataProvider vehicleFuelFillUpCreateDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param float $odo_meter_reading
     * @param float $litres
     * @param float $price
     * @param float $station_latitude
     * @param float $station_longitude
     * @param string $response_status
     */
    public function testVehicleFuelFillUpCreate($user_logged_in, $license_plate_no, $odo_meter_reading, $litres, $price, $station_latitude, $station_longitude, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $fuel_fill_up = array();
        if($odo_meter_reading != null) {
            $fuel_fill_up['odo_meter_reading'] = $odo_meter_reading;
        }
        if($litres != null) {
            $fuel_fill_up['litres'] = $litres;
        }
        if($price != null) {
            $fuel_fill_up['price'] = $price;
        }
        if($station_latitude != null) {
            $fuel_fill_up['station_latitude'] = $station_latitude;
        }
        if($station_longitude != null) {
            $fuel_fill_up['station_longitude'] = $station_longitude;
        }
        if(sizeof($fuel_fill_up) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('fuel_fill_up' => $fuel_fill_up));
        }

        // Requesting
        $this->client->request('POST', '/vehicle/' . $license_plate_no . '/fuel-fill-up/' , array(), array(),
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
     * For providing data for testing src\FuelFillUpBundle\Controller\ManageController createAction creating check in
     *
     * @return array
     */
    public function vehicleFuelFillUpCreateDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not create a new check in the check in table of the database
             * For when the user trying to create the check in is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN10', 2456.12, 10, 94.23, 7.8465, 80.6543, $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not create a new check in the check in table of the database
             * For when the details of the vehicle to be created is not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array(true, 'TEST-LPN10', null, null, null, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
            /*
             * Should not create a new check in the check in table of the database
             * For when the vehicle the check in is requested to be created for does not exist
             *
             * The session should exist
             */
            'VehicleDoesNotExist' => array(true, 'TEST-NELPN', 2456.12, 10, 94.23, 7.8465, 80.6543, $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not create a new check in the check in table of the database
             * For when the vehicle the check in is requested to be created for is not owned by the user or the user is not a driver of it
             *
             * The session should exist
             */
            'UserNotADriverOrOwner' => array(true, 'TEST-LPN20', 2456.12, 10, 94.23, 7.8465, 80.6543, $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER),
            /*
             * Should create a new check in the check in table of the database
             * For when the check in details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN10', 2456.12, 10, 94.23, 7.8465, 80.6543, $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\FuelFillUpBundle\Controller\ManageController updateAction updating check in
     *
     * @dataProvider vehicleFuelFillUpUpdateDataProvider
     *
     * @param string $license_plate_no
     * @param int $fuel_fill_up_id
     * @param boolean $user_logged_in
     * @param string $odo_meter_reading
     * @param string $litres
     * @param string $price
     * @param string $response_status
     */
    public function testVehicleFuelFillUpUpdate($license_plate_no, $fuel_fill_up_id, $user_logged_in, $odo_meter_reading, $litres, $price, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $fuel_fill_up = array();
        if($odo_meter_reading != null) {
            $fuel_fill_up['odo_meter_reading'] = $odo_meter_reading;
        }
        if($litres != null) {
            $fuel_fill_up['litres'] = $litres;
        }
        if($price != null) {
            $fuel_fill_up['price'] = $price;
        }
        if(sizeof($fuel_fill_up) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('fuel_fill_up' => $fuel_fill_up));
        }

        // Requesting
        $this->client->request('PUT', '/vehicle/' . $license_plate_no . '/fuel-fill-up/' . $fuel_fill_up_id . '/', array(), array(),
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
     * For providing data for testing src\FuelFillUpBundle\Controller\ManageController updateAction updating check in
     *
     * @return array
     */
    public function vehicleFuelFillUpUpdateDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not update the details of the check in
             * For when the user trying to update the check in is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array('TEST-LPN10', 31, false, 2456.12, 10, 94.23, $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not update the details of the check in
             * For when the details of the check in to be updated are not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array('TEST-LPN10', 31, true, null, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
            /*
             * Should not update the details of the check in
             * For when the vehicle the check in is requested to be updated for does not exist
             *
             * The session should exist
             */
            'NonExistentVehicle' => array('TEST-NELPN', 31, true, 2456.12, 10, 94.23, $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not update the details of the check in
             * For when the vehicle the check in is requested to be updated for is not owned by the user or the user is not a driver of it
             *
             * The session should exist
             */
            'UserNotADriverOrOwner' => array('TEST-LPN20', 31, true, 2456.12, 10, 94.23, $constants->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER),
            /*
             * Should not update the details of the check in
             * For when the check in to be updated does not exist
             *
             * The session should exist
             */
            'FuelFillUpDoesNotExist' => array('TEST-LPN10', 32, true, 2456.12, 10, 94.23, $constants->response->STATUS_FUEL_FILL_UP_DOES_NOT_EXIST),
            /*
             * Should not update the details of the check in
             * For when the check in to be updated does not exist
             *
             * The session should exist
             */
            'FuelFillUpNotCreator' => array('TEST-LPN10', 30, true, 2456.12, 10, 94.23, $constants->response->STATUS_FUEL_FILL_UP_NOT_CREATOR),
            /*
             * Should update the details of the check in
             * For when the vehicle details are3 provided
             *
             * The session should exist
             */
            'Success' => array('TEST-LPN10', 31, true, 2456.12, 10, 94.23, $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\FuelFillUpBundle\Controller\ManageController removeAction removing check in
     *
     * @dataProvider vehicleFuelFillUpRemoveDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param int $fuel_fill_up_id
     * @param string $response_status
     */
    public function testVehicleFuelFillUpRemove($user_logged_in, $license_plate_no, $fuel_fill_up_id, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('DELETE', '/vehicle/' . $license_plate_no  . '/fuel-fill-up/' . $fuel_fill_up_id . '/', array(), array(),
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
     * For providing data for testing src\FuelFillUpBundle\Controller\ManageController deleteAction deleting vehicle
     *
     * @return array
     */
    public function vehicleFuelFillUpRemoveDataProvider() {
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
            'NonExistentCheckIn' => array(true, 'TEST-LPN10', 32, $constants->response->STATUS_FUEL_FILL_UP_DOES_NOT_EXIST),
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
            'FuelFillUpNotAssignedToSpecifiedVehicle' => array(true, 'TEST-LPN11', 31, $constants->response->STATUS_FUEL_FILL_UP_NOT_ASSIGNED_TO_VEHICLE),
            /*
             * Should not delete the vehicle
             * For when the user trying to remove the check in is not the creator of the check in
             *
             * The session should exist
             */
            'UserNotCreatorOfCheckIn' => array(true, 'TEST-LPN10', 11, $constants->response->STATUS_FUEL_FILL_UP_NOT_CREATOR),
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