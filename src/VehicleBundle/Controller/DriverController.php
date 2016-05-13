<?php

namespace VehicleBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller {
    /**
     * Adds an existing user as a driver to an existing vehicle
     * 
     * A new entry will be added to the driver table which represents a many to many mapping between vehicle and user entities
     * A logged in user can assign any user as a driver to a vehicle the user owns
     * Only the owner can perform this for each vehicle
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request) {
        $request_data = json_decode($request->getContent());

        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->vehicle) && isset($request_data->vehicle->license_plate_no) && isset($request_data->user) && isset($request_data->user->username)) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($request_data->vehicle->license_plate_no);
                $driver = $this->getDoctrine()->getRepository($this->get('constants')->database->USER_REPOSITORY)->find($request_data->user->username);

                if (isset($vehicle)) {
                    if ($vehicle->getOwner()->getUsername() == $user->getUsername()) {
                        if (isset($driver)) {
                            if ($driver->getUsername() != $user->getUsername()) {
                                $vehicle->addDriver($driver);

                                try {
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($vehicle);
                                    $em->flush();
                                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                                } catch (UniqueConstraintViolationException $e) {
                                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_DUPLICATE_DRIVER;
                                }
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_VEHICLE_OWNER_CANNOT_BE_A_DRIVER;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_USER_NOT_REGISTERED;
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
     * Removes an existing driver from a vehicle
     *
     * The entry will be removed from the driver table which represents a many to many mapping between vehicle and user entities
     * A logged in user can remove any driver from a vehicle the user owns
     * Only the owner can perform this for each vehicle
     *
     * @param Request $request
     * @return Response
     */
    public function removeAction(Request $request) {
        $request_data = json_decode($request->getContent());

        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->vehicle) && isset($request_data->vehicle->license_plate_no) && isset($request_data->user) && isset($request_data->user->username)) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($request_data->vehicle->license_plate_no);
                $driver = $this->getDoctrine()->getRepository($this->get('constants')->database->USER_REPOSITORY)->find($request_data->user->username);

                if (isset($vehicle)) {
                    if ($vehicle->getOwner()->getUsername() == $user->getUsername()) {
                        if (isset($driver)) {
                            if ($driver->getUsername() != $user->getUsername()) {
                                $vehicle->removeDriver($driver);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($vehicle);
                                $em->flush();
                                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_VEHICLE_OWNER_CANNOT_BE_A_DRIVER;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_USER_NOT_REGISTERED;
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
}