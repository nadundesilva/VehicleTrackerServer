<?php

namespace VehicleBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller {
    /**
     * Returns all existing drivers of the vehicle specified
     *
     * @param string $license_plate_no
     * @return Response
     */
    public function getAllAction($license_plate_no) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $vehicle = $this->getDoctrine()
                ->getManager()
                ->getRepository((new Retriever())->database->VEHICLE_REPOSITORY)
                ->find($license_plate_no);
            if (isset($vehicle)) {
                $drivers_list = $vehicle->getDriver();
                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
            } else {
                $response_text = $this->get('constants')->response->STATUS_VEHICLE_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($drivers_list)) {
            $response_body[$this->get('constants')->response->DRIVERS] = $drivers_list;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Returns all a search of users who are not drivers
     *
     * @param string $license_plate_no
     * @param string $username_search_key;
     * @return Response
     */
    public function getAllForAddingDriverAction($license_plate_no, $username_search_key) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $vehicle = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->VEHICLE_REPOSITORY)->find($license_plate_no);
            if (isset($vehicle)) {
                if ($vehicle->getOwner()->getUsername() == $user->getUsername()) {
                    $users = $this->getDoctrine()->getManager()
                        ->createQuery('SELECT DISTINCT driver.username AS username, driver.firstName AS first_name, driver.lastName AS last_name FROM VehicleBundle:Vehicle AS vehicle JOIN vehicle.driver AS driver WHERE driver.username LIKE :username')
                        ->setParameter('username', '%' . $username_search_key . '%')
                        ->getArrayResult();
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

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($users)) {
            $response_body[$this->get('constants')->response->USERS] = $users;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

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
                            $response_text = $this->get('constants')->response->STATUS_VEHICLE_OWNER_CANNOT_BE_DRIVER;
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
                            $driver_list_size = sizeof($driver_list);
                            for ($i = 0; $i < $driver_list_size; $i++) {
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
                                $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_VEHICLE_OWNER_CANNOT_BE_DRIVER;
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
