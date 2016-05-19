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
     * Adds a new vehicle
     *
     * if an add request had been sent a new vehicle will be added
     *
     * if an update request had been sent a the vehicle will be retrieved and updated
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->vehicle) && isset($request_data->vehicle->name) && isset($request_data->vehicle->license_plate_no) && isset($request_data->vehicle->description) && isset($request_data->vehicle->fuel_one) && (!isset($request_data->vehicle->bi_fuel) || !$request_data->vehicle->bi_fuel || isset($request_data->vehicle->fuel_two)) && isset($request_data->vehicle->make) && isset($request_data->vehicle->model) && isset($request_data->vehicle->year)) {
                $vehicle = (new Vehicle())
                    ->setName($request_data->vehicle->name)
                    ->setLicensePlateNo($request_data->vehicle->license_plate_no)
                    ->setDescription($request_data->vehicle->description)
                    ->setFuelOne($request_data->vehicle->fuel_one)
                    ->setMake($request_data->vehicle->make)
                    ->setModel($request_data->vehicle->model)
                    ->setYear($request_data->vehicle->year)
                    ->setOwner($user);
                if (isset($request_data->vehicle->bi_fuel) && $request_data->vehicle->bi_fuel) {
                    $vehicle->setFuelTwo($request_data->vehicle->fuel_two);
                }
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($vehicle);
                    $em->flush();
                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                } catch (UniqueConstraintViolationException $e) {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_DUPLICATE_LICENSE_PLATE_NO;
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
     * Updates an existing vehicle
     *
     * @param Request $request
     * @param string $license_plate_no
     * @return Response
     */
    public function updateAction(Request $request, $license_plate_no) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->vehicle) && isset($request_data->vehicle->name) && isset($request_data->vehicle->license_plate_no) && isset($request_data->vehicle->description) && isset($request_data->vehicle->fuel_one) && (!isset($request_data->vehicle->bi_fuel) || !$request_data->vehicle->bi_fuel || isset($request_data->vehicle->fuel_two)) && isset($request_data->vehicle->make) && isset($request_data->vehicle->model) && isset($request_data->vehicle->year)) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
                if (isset($vehicle)) {
                    if ($vehicle->getOwner()->getUsername() == $user->getUsername()) {
                        $vehicle->setName($request_data->vehicle->name)
                            ->setLicensePlateNo($request_data->vehicle->license_plate_no)
                            ->setDescription($request_data->vehicle->description)
                            ->setFuelOne($request_data->vehicle->fuel_one)
                            ->setMake($request_data->vehicle->make)
                            ->setModel($request_data->vehicle->model)
                            ->setYear($request_data->vehicle->year)
                            ->setOwner($user);
                        if (isset($request_data->vehicle->bi_fuel) && $request_data->vehicle->bi_fuel) {
                            $vehicle->setFuelTwo($request_data->vehicle->fuel_two);
                        }
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
     * Removes an existing vehicle
     *
     * @param string $license_plate_no
     * @return Response
     */
    public function removeAction($license_plate_no) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);

            if (isset($vehicle)) {
                if ($vehicle->getOwner()->getUsername() == $user->getUsername()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($vehicle);
                    $em->flush();
                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_OWNED;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_VEHICLE_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
