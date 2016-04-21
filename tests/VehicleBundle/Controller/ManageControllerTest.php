<?php

namespace Tests\VehicleBundle\Controller;

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
     * Should not create a new vehicle in the vehicle table of the database
     * For when the user trying to create the vehicle is not logged in
     *
     * The session does not exist
     */
    public function testVehicleCreateForUserNotLoggedIn() {
        // Requesting
        $this->client->request('POST', '/vehicle/create', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('vehicle' => array(
                'name' => 'testNewVehicle',
                'license_plate' => 'TEST-NLPN',
                'description' => 'testNewDescription',
                'bi_fuel' => true,
                'fuel_one' => 'testNewFuelOne',
                'fuel_two' => 'testNewFuelTwo',
                'make' => 'testNewMake',
                'model' => 'testNewModel',
                'year' => '2010',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_USER_NOT_LOGGED_IN, json_decode($response->getContent())->status);
        $this->assertNull($this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not create a new vehicle in the vehicle table of the database
     * For when the details of the vehicle to be created is not given
     *
     * The session should exist
     */
    public function testVehicleCreateForDetailsNotGiven() {
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser0');

        // Requesting
        $this->client->request('POST', '/vehicle/create', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_NO_ARGUMENTS_PROVIDED, json_decode($response->getContent())->status);
        $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should not create a new vehicle in the vehicle table of the database
     * For when the vehicle details are provided with duplicate license plate no
     *
     * The session should exist
     */
    public function testVehicleCreateForDuplicateLicensePlateNo() {
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser0');

        // Requesting
        $this->client->request('POST', '/vehicle/create', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('vehicle' => array(
                'name' => 'testNewVehicle',
                'license_plate' => 'TEST-LPN0',
                'description' => 'testNewDescription',
                'bi_fuel' => true,
                'fuel_one' => 'testNewFuelOne',
                'fuel_two' => 'testNewFuelTwo',
                'make' => 'testNewMake',
                'model' => 'testNewModel',
                'year' => '2010',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO, json_decode($response->getContent())->status);
        $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
    }

    /**
     * Functional Test
     *
     * Should create a new vehicle in the vehicle table of the database
     * For when the vehicle details are provided
     *
     * The session should exist
     */
    public function testVehicleCreateForDetailsGiven() {
        // Creating a mock session
        $this->session->set($this->constants->session->USERNAME, 'testUser0');

        // Requesting
        $this->client->request('POST', '/vehicle/create', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ),
            json_encode(array('vehicle' => array(
                'name' => 'testNewVehicle',
                'license_plate' => 'TEST-NLPN',
                'description' => 'testNewDescription',
                'bi_fuel' => true,
                'fuel_one' => 'testNewFuelOne',
                'fuel_two' => 'testNewFuelTwo',
                'make' => 'testNewMake',
                'model' => 'testNewModel',
                'year' => '2010',
            )))
        );
        $response = $this->client->getResponse();

        // Assertions
        $this->assertSuccessfulResponse($response);
        $this->assertEquals($this->constants->response->STATUS_SUCCESS, json_decode($response->getContent())->status);
        $this->assertEquals('testUser0', $this->session->get($this->constants->session->USERNAME));
    }
}