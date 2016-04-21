<?php

namespace VehicleBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use VehicleBundle\Entity\Vehicle;

/*
 * For handling vehicle managing functionalities
 *
 * add/update vehicle
 * delete vehicle
 */
class ManageController extends Controller {
    /**
     * Adds a new vehicle or updates an existing vehicle
     *
     * if an add request had been sent a new vehicle will be added
     *
     * if an update request had been sent a the vehicle will be retrieved and updated
     *
     * @param Request $request
     * @return Response
     */
    public function createUpdateAction(Request $request) {
        $request_data = json_decode($request->getContent());

        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data)) {
                $name = $request_data->vehicle->name;
                $license_plate = $request_data->vehicle->license_plate;
                $description = $request_data->vehicle->description;
                $fuel_one = $request_data->vehicle->fuel_one;
                if (isset($request_data->vehicle->bi_fuel) && $request_data->vehicle->bi_fuel) {
                    $fuel_two = $request_data->vehicle->fuel_two;
                } else {
                    $fuel_two = null;
                }
                $make = $request_data->vehicle->make;
                $model = $request_data->vehicle->model;
                $year = $request_data->vehicle->year;
                if ($request->isMethod('POST')) {
                    $vehicle = new Vehicle();
                } else {
                    $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate);
                }
                if (isset($vehicle)) {
                    if ($request->isMethod('POST') || ($request->isMethod('PUT') && $vehicle->getOwner()->getUsername() == $this->get('session')->get($this->get('constants')->session->USERNAME))) {
                        $vehicle->setName($name)
                            ->setLicensePlateNo($license_plate)
                            ->setDescription($description)
                            ->setFuelOne($fuel_one)
                            ->setMake($make)
                            ->setModel($model)
                            ->setYear($year)
                            ->setOwner($user)
                            ->setFuelTwo($fuel_two);

                        try {
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($vehicle);
                            $em->flush();
                            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                        } catch (UniqueConstraintViolationException $e) {
                            $response_text = $this->get('constants')->response->STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_OWNED;
                    }
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_DOES_NOT_EXIST;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_NO_ARGUMENTS_PROVIDED;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Deletes an existing vehicle
     *
     * @param Request $request
     */
    public function deleteAction(Request $request) {

    }
}
