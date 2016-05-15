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
     * @param string $license_plate_no
     * @param string $username
     * @return Response
     */
    public function addAction($license_plate_no, $username) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
            $driver = $this->getDoctrine()->getRepository($this->get('constants')->database->USER_REPOSITORY)->find($username);

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
     * @param string $license_plate_no
     * @param string $username
     * @return Response
     */
    public function removeAction($license_plate_no, $username) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
                $driver = $this->getDoctrine()->getRepository($this->get('constants')->database->USER_REPOSITORY)->find($username);

            if (isset($vehicle)) {
                if ($vehicle->getOwner()->getUsername() == $user->getUsername()) {
                    if (isset($driver)) {
                        if ($driver->getUsername() != $user->getUsername()) {
                            $driver_list = $vehicle->getDriver();
                            $driver_exist = false;
                            for ($i = 0; $i < sizeof($driver_list); $i++) {
                                if ($driver_list[$i]->getUsername() == $driver->getUsername()) {
                                    $driver_exist = true;
                                }
                            }

                            if ($driver_exist) {
                                $vehicle->removeDriver($driver);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($vehicle);
                                $em->flush();
                                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_A_DRIVER;
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
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}