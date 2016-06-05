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
class ManageControllerTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\ManageController createAction creating vehicle
     *
     * @dataProvider vehicleCreateDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $vehicle_name
     * @param string $license_plate_no
     * @param string $description
     * @param boolean $bi_fuel
     * @param string $fuel_one
     * @param string $fuel_two
     * @param string $make
     * @param string $model
     * @param string $year
     * @param string $response_status
     */
    public function testVehicleCreate($user_logged_in, $vehicle_name, $license_plate_no, $description, $bi_fuel, $fuel_one, $fuel_two, $make, $model, $year, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $vehicle = array();
        if($vehicle_name != null) {
            $vehicle['name'] = $vehicle_name;
        }
        if($license_plate_no != null) {
            $vehicle['license_plate_no'] = $license_plate_no;
        }
        if($description != null) {
            $vehicle['description'] = $description;
        }
        if($bi_fuel != null) {
            $vehicle['bi_fuel'] = $bi_fuel;
        }
        if($fuel_one != null) {
            $vehicle['fuel_one'] = $fuel_one;
        }
        if($fuel_two != null) {
            $vehicle['fuel_two'] = $fuel_two;
        }
        if($make != null) {
            $vehicle['make'] = $make;
        }
        if($model != null) {
            $vehicle['model'] = $model;
        }
        if($year != null) {
            $vehicle['year'] = $year;
        }
        if(sizeof($vehicle) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('vehicle' => $vehicle));
        }

        // Requesting
        $this->client->request('POST', '/vehicle/', array(), array(),
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
     * For providing data for testing src\VehicleBundle\Controller\ManageController createAction creating vehicle
     *
     * @return array
     */
    public function vehicleCreateDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not create a new vehicle in the vehicle table of the database
             * For when the user trying to create the vehicle is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'testNewVehicle', 'TEST-NLPN', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not create a new vehicle in the vehicle table of the database
             * For when the details of the vehicle to be created is not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array(true, null, null, null, null, null, null, null, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
            /*
             * Should not create a new vehicle in the vehicle table of the database
             * For when the vehicle details are provided with duplicate license plate no
             *
             * The session should exist
             */
            'DuplicateLicensePlateNo' => array(true, 'testNewVehicle', 'TEST-LPN00', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO),
            /*
             * Should create a new vehicle in the vehicle table of the database
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array(true, 'testNewVehicle', 'TEST-NLPNC', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\ManageController updateAction updating vehicle
     *
     * @dataProvider vehicleUpdateDataProvider
     *
     * @param $original_license_plate_no
     * @param boolean $user_logged_in
     * @param string $vehicle_name
     * @param string $license_plate_no
     * @param string $description
     * @param boolean $bi_fuel
     * @param string $fuel_one
     * @param string $fuel_two
     * @param string $make
     * @param string $model
     * @param string $year
     * @param string $response_status
     */
    public function testVehicleUpdate($original_license_plate_no, $user_logged_in, $vehicle_name, $license_plate_no, $description, $bi_fuel, $fuel_one, $fuel_two, $make, $model, $year, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Creating request body
        $vehicle = array();
        if($vehicle_name != null) {
            $vehicle['name'] = $vehicle_name;
        }
        if($license_plate_no != null) {
            $vehicle['license_plate_no'] = $license_plate_no;
        }
        if($description != null) {
            $vehicle['description'] = $description;
        }
        if($bi_fuel != null) {
            $vehicle['bi_fuel'] = $bi_fuel;
        }
        if($fuel_one != null) {
            $vehicle['fuel_one'] = $fuel_one;
        }
        if($fuel_two != null) {
            $vehicle['fuel_two'] = $fuel_two;
        }
        if($make != null) {
            $vehicle['make'] = $make;
        }
        if($model != null) {
            $vehicle['model'] = $model;
        }
        if($year != null) {
            $vehicle['year'] = $year;
        }
        if(sizeof($vehicle) == 0) {
            $content = null;
        } else {
            $content = json_encode(array('vehicle' => $vehicle));
        }

        // Requesting
        $this->client->request('PUT', '/vehicle/' . $original_license_plate_no . '/', array(), array(),
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
     * For providing data for testing src\VehicleBundle\Controller\ManageController updateAction updating vehicle
     *
     * @return array
     */
    public function vehicleUpdateDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not update the details of the vehicle
             * For when the user trying to update the vehicle is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array('TEST-LPN00', false, 'testNewVehicle', 'TEST-NLPN', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not update the details of the vehicle
             * For when the details of the vehicle to be updated are not given
             *
             * The session should exist
             */
            'DetailsNotGiven' => array('TEST-LPN00', true, null, null, null, null, null, null, null, null, null, $constants->response->STATUS_NO_ARGUMENTS_PROVIDED),
            /*
             * Should not update the details of the vehicle
             * For when the vehicle requested to be updated does not exist
             *
             * The session should exist
             */
            'NonExistentVehicle' => array('TEST-NELPN', true, 'testNonExistentVehicle', 'TEST-NLPN', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not update the details of the vehicle
             * For when the vehicle requested to be updates is not owned by the user
             *
             * The session should exist
             */
            'NotOwnedVehicle' => array('TEST-LPN10', true, 'testNonExistentVehicle', 'TEST-NLPN', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_VEHICLE_NOT_OWNED),
            /*
             * Should not update the details of the vehicle
             * For when the new license plate number provided already exists
             *
             * The session should exist
             */
            'DuplicateLicensePlateNo' => array('TEST-LPN00', true, 'testNewVehicle', 'TEST-LPN01', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO),
            /*
             * Should update the details of the vehicle
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'Success' => array('TEST-NLPNC', true, 'testNewVehicle', 'TEST-NLPNU', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_SUCCESS),
        );
    }

    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\ManageController removeAction removing vehicle
     *
     * @dataProvider vehicleRemoveDataProvider
     *
     * @param boolean $user_logged_in
     * @param string $license_plate_no
     * @param string $response_status
     */
    public function testVehicleRemove($user_logged_in, $license_plate_no, $response_status) {
        if($user_logged_in) {
            // Creating a mock session
            $this->session->set($this->constants->session->USERNAME, 'testUser0');
        }

        // Requesting
        $this->client->request('DELETE', '/vehicle/' . $license_plate_no  . '/', array(), array(),
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
    public function vehicleRemoveDataProvider() {
        $constants = new Retriever();

        return array(
            /*
             * Should not delete the vehicle
             * For when the user trying to delete the vehicle is not logged in
             *
             * The session does not exist
             */
            'UserNotLoggedIn' => array(false, 'TEST-LPN00', $constants->response->STATUS_USER_NOT_LOGGED_IN),
            /*
             * Should not delete the vehicle
             * For when the vehicle to be deleted does not exist
             *
             * The session should exist
             */
            'NonExistentVehicle' => array(true, 'TEST-NELPN', $constants->response->STATUS_VEHICLE_DOES_NOT_EXIST),
            /*
             * Should not delete the vehicle
             * For when the vehicle to be deleted is not owned by the user
             *
             * The session should exist
             */
            'NotOwnedVehicle' => array(true, 'TEST-LPN10', $constants->response->STATUS_VEHICLE_NOT_OWNED),
            /*
             * Should delete the vehicle
             * For when the vehicle license plate no is provided
             *
             * The session should exist
             */
            'Success' => array(true, 'TEST-NLPNU', $constants->response->STATUS_SUCCESS),
        );
    }
}