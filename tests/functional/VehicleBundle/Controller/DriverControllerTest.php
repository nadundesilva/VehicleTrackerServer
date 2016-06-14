<?php

namespace Tests\VehicleBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the vehicle  driver controller
 * src\VehicleBundle\Controller\DriverController
 */
class DriverControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\DriverController getAllAction get all drivers assigned for a specific vehicle
     *
     * @dataProvider vehicleDriverGetAllDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param int $response_status
     * @param int $drivers_count
     */
    public function testVehicleDriverGetAll($user_logged_in, $license_plate_no, $response_status, $drivers_count) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/driver/' , array(), array(),
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
        if($drivers_count != null) {
            $this->assertEquals($drivers_count, sizeof($results->drivers));
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
     * For providing data for testing src\VehicleBundle\Controller\DriverController getAllAction getting all drivers for a specific vehicle
     *
     * @return array
     */
    public function vehicleDriverGetAllDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not retrieve the drivers for the vehicle specified
             * For when the user trying to create the vehicle is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN00', $constants->response->STATUS_USER_NOT_LOGGED_IN, null),
            /*
             * Should not retrieve the drivers for the vehicle specified
             * For when the vehicle the user is trying to retrieve the drivers for does not exist
             *
             * The session does not exist
             */
            'VehicleDoesNotExist' => array(true, 'TEST-NLPN', $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST, null),
            /*
             * Should retrieve the drivers for the vehicle specified
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN00', $constants->response->STATUS_SUCCESS, 1),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\DriverController getAllAction get all drivers assigned for a specific vehicle
     *
     * @dataProvider vehicleNewDriverUserSearchDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $username_search_key
     * @param int $response_status
     * @param int $users_count
     */
    public function testVehicleNewDriverUserSearch($user_logged_in, $license_plate_no, $username_search_key, $response_status, $users_count) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('GET', '/vehicle/' . $license_plate_no . '/driver/user/' . $username_search_key . '/' , array(), array(),
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
        if($users_count != null) {
            $this->assertEquals($users_count, sizeof($results->users));
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
     * For providing data for testing src\VehicleBundle\Controller\DriverController getAllAction getting all drivers for a specific vehicle
     *
     * @return array
     */
    public function vehicleNewDriverUserSearchDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not retrieve the drivers for the vehicle specified
             * For when the user trying to create the vehicle is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN00', 'test', $constants->response->STATUS_USER_NOT_LOGGED_IN, null),
            /*
             * Should not retrieve the drivers for the vehicle specified
             * For when the user trying to retrieve the driver list does not own the vehicle
             *
             * The session does not exist
             */
            'VehicleNotOwned' => array(true, 'TEST-LPN10', 'test', $constants->response->STATUS_VEHICLE_NOT_OWNED, null),
            /*
             * Should not retrieve the drivers for the vehicle specified
             * For when the vehicle the user is trying to retrieve the drivers for does not exist
             *
             * The session does not exist
             */
            'VehicleDoesNotExist' => array(true, 'TEST-NLPN', 'test', $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST, null),
            /*
             * Should retrieve the drivers for the vehicle specified
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN00', 'test', $constants->response->STATUS_SUCCESS, 1),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\DriverController addAction adding driver
     *
     * @dataProvider vehicleDriverAddDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $driver_username
     * @param string $response_status
     * @internal param string $vehicle_name
     */
    public function testVehicleDriverAdd($user_logged_in, $license_plate_no, $driver_username, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('POST', '/vehicle/' . $license_plate_no . '/driver/' . $driver_username . '/' , array(), array(),
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
     * For providing data for testing src\VehicleBundle\Controller\ManageController createUpdateAction creating vehicle
     *
     * @return array
     */
    public function vehicleDriverAddDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not add the driver into the driver table of the database
             * For when the user trying to add the driver is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN00', 'testUser2', $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not add the driver into the driver table of the database
             * For when the vehicle the driver is requested to be added to does not exist
             *
             * The session should exist
             */
            'VehicleDoesNotExist' => array(true, 'TEST-NLPN', 'testUser2', $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the vehicle the driver is requested to be added to is not owned by the user trying to add the driver
             *
             * The session should exist
             */
            'VehicleNotOwned' => array(true, 'TEST-LPN20', 'testUser1', $constants->response->STATUS_VEHICLE_NOT_OWNED),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be added as a driver is not registered in the system
             *
             * The session should exist
             */
            'DriverNotRegistered' => array(true, 'TEST-LPN00', 'testNonExistentUser', $constants->response->STATUS_USER_NOT_REGISTERED),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be added as a driver is already a driver of the vehicle
             *
             * The session should exist
             */
            'OwnerCannotBeADriver' => array(true, 'TEST-LPN00', 'testUser0', $constants->response->STATUS_VEHICLE_OWNER_CANNOT_BE_DRIVER),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be added as a driver is already a driver of the vehicle
             *
             * The session should exist
             */
            'DuplicateDriver' => array(true, 'TEST-LPN00', 'testUser1', $constants->response->STATUS_VEHICLE_DUPLICATE_DRIVER),
            /*
             * Should add the driver into the driver table of the database
             * For when the details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN00', 'testUser2', $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\DriverController removeAction removing driver
     *
     * @dataProvider vehicleDriverRemoveDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $driver_username
     * @param string $response_status
     * @internal param string $vehicle_name
     */
    public function testVehicleDriverRemove($user_logged_in, $license_plate_no, $driver_username, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('DELETE', '/vehicle/' . $license_plate_no . '/driver/' . $driver_username . '/', array(), array(),
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
     * For providing data for testing src\VehicleBundle\Controller\ManageController createUpdateAction creating vehicle
     *
     * @return array
     */
    public function vehicleDriverRemoveDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not add the driver into the driver table of the database
             * For when the user trying to add the driver is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN00', 'testUser2', $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not add the driver into the driver table of the database
             * For when the vehicle the driver is requested to be added to does not exist
             *
             * The session should exist
             */
            'VehicleDoesNotExist' => array(true, 'TEST-NLPN', 'testUser2', $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the vehicle the driver is requested to be added to is not owned by the user trying to add the driver
             *
             * The session should exist
             */
            'VehicleNotOwned' => array(true, 'TEST-LPN20', 'testUser1', $constants->response->STATUS_VEHICLE_NOT_OWNED),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be added as a driver is not registered in the system
             *
             * The session should exist
             */
            'DriverNotRegistered' => array(true, 'TEST-LPN00', 'testNonExistentUser', $constants->response->STATUS_USER_NOT_REGISTERED),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be removed as a driver is 
             *
             * The session should exist
             */
            'OwnerCannotBeADriver' => array(true, 'TEST-LPN00', 'testUser0', $constants->response->STATUS_VEHICLE_OWNER_CANNOT_BE_DRIVER),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be added as a driver is not a driver of the vehicle
             *
             * The session should exist
             */
            'UserToBeRemovedNotADriver' => array(true, 'TEST-LPN00', 'testUser3', $constants->response->STATUS_VEHICLE_NOT_DRIVER),
            /*
             * Should add the driver into the driver table of the database
             * For when the details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-LPN00', 'testUser2', $constants->response->STATUS_SUCCESS),
        );
    }
}