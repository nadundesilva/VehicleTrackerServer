<?php

namespace Tests\VehicleBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Tests\BaseFunctionalTest;

/*
 * Functional Tests
 *
 * For testing the vehicle manage controller
 * src\VehicleBundle\Controller\ManageController
 */
class DriverControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\DriverController addAction adding driver
     *
     * @dataProvider vehicleDriverAddDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $driver_name
     * @param string $response_status
     * @internal param string $vehicle_name
     */
    public function testVehicleDriverAdd($user_logged_in, $license_plate_no, $driver_name, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $content = array();
        if($license_plate_no != null) {
            $content['vehicle'] = array('license_plate_no' => $license_plate_no);
        }
        if($driver_name != null) {
            $content['user'] = array('username' => $driver_name);
        }
        $content = json_encode($content);

        // Requesting
        $this->client->request('POST', '/vehicle/driver/add', array(), array(),
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
             * For when the details of the vehicle and the user to be added is not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array(true, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
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
            'OwnerCannotBeADriver' => array(true, 'TEST-LPN00', 'testUser0', $constants->response->STATUS_VEHICLE_OWNER_CANNOT_BE_A_DRIVER),
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
     * @param string $driver_name
     * @param string $response_status
     * @internal param string $vehicle_name
     */
    public function testVehicleDriverRemove($user_logged_in, $license_plate_no, $driver_name, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $content = array();
        if($license_plate_no != null) {
            $content['vehicle'] = array('license_plate_no' => $license_plate_no);
        }
        if($driver_name != null) {
            $content['user'] = array('username' => $driver_name);
        }
        $content = json_encode($content);

        // Requesting
        $this->client->request('DELETE', '/vehicle/driver/remove', array(), array(),
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
             * For when the details of the vehicle and the user to be added is not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array(true, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
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
             * For when the user to be removed as a driver is the owner
             *
             * The session should exist
             */
            'OwnerCannotBeADriver' => array(true, 'TEST-LPN00', 'testUser0', $constants->response->STATUS_VEHICLE_OWNER_CANNOT_BE_A_DRIVER),
            /*
             * Should not add the driver into the vehicle table of the database
             * For when the user to be added as a driver is not a driver of the vehicle
             *
             * The session should exist
             */
            'UserToBeRemovedNotADriver' => array(true, 'TEST-LPN00', 'testUser3', $constants->response->STATUS_VEHICLE_NOT_A_DRIVER),
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