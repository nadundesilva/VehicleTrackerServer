<?php

namespace FuelFillUpBundle\Controller;

use CheckInBundle\Entity\CheckIn;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManageController extends Controller {
    /**
     * Adds a new check in for a specific vehicle
     *
     * @param Request $request
     * @param string $license_plate_no
     * @return Response
     */
    public function createAction(Request $request, $license_plate_no) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->check_in) && isset($request_data->check_in->description) && isset($request_data->check_in->latitude) && isset($request_data->check_in->longitude)) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
                if (isset($vehicle)) {
                    $driver_list = $vehicle->getDriver();
                    $driver_list_size = sizeof($driver_list);
                    for ($i = 0; $i < $driver_list_size; $i++) {
                        if ($driver_list[$i]->getUsername() == $user->getUsername()) {
                            $is_a_driver = true;
                            break;
                        }
                    }
                    if (isset($is_a_driver) || $vehicle->getOwner()->getUsername() == $user->getUsername()) {
                        $check_in = (new CheckIn())
                            ->setDescription($request_data->check_in->description)
                            ->setLat($request_data->check_in->latitude)
                            ->setLong($request_data->check_in->longitude)
                            ->setTimestamp(new \DateTime())
                            ->setVehicle($vehicle)
                            ->setCreator($user);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($check_in);
                        $em->flush();
                        $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
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
     * Updates a check in for a specific vehicle
     *
     * @param Request $request
     * @param string $license_plate_no
     * @param int $check_in_id
     * @return Response
     */
    public function updateAction(Request $request, $license_plate_no, $check_in_id) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->check_in) && isset($request_data->check_in->description)) {
                $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
                if (isset($vehicle)) {
                    $driver_list = $vehicle->getDriver();
                    $driver_list_size = sizeof($driver_list);
                    for ($i = 0; $i < $driver_list_size; $i++) {
                        if ($driver_list[$i]->getUsername() == $user->getUsername()) {
                            $is_a_driver = true;
                            break;
                        }
                    }
                    if (isset($is_a_driver) || $vehicle->getOwner()->getUsername() == $user->getUsername()) {
                        $check_in = $this->getDoctrine()->getRepository($this->get('constants')->database->CHECK_IN_REPOSITORY)->find($check_in_id);
                        if (isset($check_in)) {
                            if ($check_in->getCreator()->getUsername() == $user->getUsername()) {
                                $check_in->setDescription($request_data->check_in->description);

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($vehicle);
                                $em->flush();
                                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_CHECK_IN_NOT_CREATOR;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_CHECK_IN_DOES_NOT_EXIST;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
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
     * Removes a check in from a specific vehicle
     *
     * @param string $license_plate_no
     * @param int $check_in_id
     * @return Response
     */
    public function removeAction($license_plate_no, $check_in_id) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $check_in = $this->getDoctrine()->getRepository($this->get('constants')->database->CHECK_IN_REPOSITORY)->find($check_in_id);
            if (isset($check_in)) {
                $vehicle = $check_in->getVehicle();
                $driver_list = $vehicle->getDriver();
                $driver_list_size = sizeof($driver_list);
                for ($i = 0; $i < $driver_list_size; $i++) {
                    if ($driver_list[$i]->getUsername() == $user->getUsername()) {
                        $is_a_driver = true;
                        break;
                    }
                }
                if (isset($is_a_driver) || $vehicle->getOwner()->getUsername() == $user->getUsername()) {
                    if ($vehicle->getLicensePlateNo() == $license_plate_no) {
                        if ($check_in->getCreator()->getUsername() == $user->getUsername()) {
                            $em = $this->getDoctrine()->getManager();
                            $em->remove($check_in);
                            $em->flush();
                            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_CHECK_IN_NOT_CREATOR;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_CHECK_IN_NOT_ASSIGNED_TO_VEHICLE;
                    }
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_CHECK_IN_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
