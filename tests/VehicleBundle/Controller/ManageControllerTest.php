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
class AuthenticationFunctionalTest extends BaseFunctionalTest {
    /**
     * Functional Test
     *
     * For testing src\VehicleBundle\Controller\ManageController createUpdateAction creating vehicle
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
            $vehicle['license_plate'] = $license_plate_no;
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
        $this->client->request('POST', '/vehicle/create', array(), array(),
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
            'DuplicateLicensePlateNo' => array(true, 'testNewVehicle', 'TEST-LPN0', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO),
            /*
             * Should create a new vehicle in the vehicle table of the database
             * For when the vehicle details are provided
             *
             * The session should exist
             */
            'DetailsGiven' => array(true, 'testNewVehicle', 'TEST-NLPN', 'testNewDescription', true, 'testNewFuelOne', 'testNewFuelTwo', 'testNewMake', 'testNewModel', '2010', $constants->response->STATUS_SUCCESS),
        );
    }
}