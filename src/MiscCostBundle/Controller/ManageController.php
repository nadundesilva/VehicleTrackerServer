<?php

namespace MiscCostBundle\Controller;

use MiscCostBundle\Entity\MiscCost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManageController extends Controller {
    /**
     * Adds a new miscellaneous cost for a specific vehicle
     *
     * @param Request $request
     * @param string $license_plate_no
     * @return Response
     */
    public function createAction(Request $request, $license_plate_no) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->misc_cost) && isset($request_data->misc_cost->type) && isset($request_data->misc_cost->value)) {
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
                        $misc_cost = (new MiscCost())
                            ->setType($request_data->misc_cost->type)
                            ->setValue($request_data->misc_cost->value)
                            ->setTimestamp(new \DateTime())
                            ->setVehicle($vehicle)
                            ->setCreator($user);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($misc_cost);
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
     * Updates a miscellaneous cost for a specific vehicle
     *
     * @param Request $request
     * @param string $license_plate_no
     * @param int $misc_cost_id
     * @return Response
     */
    public function updateAction(Request $request, $license_plate_no, $misc_cost_id) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->misc_cost) && isset($request_data->misc_cost->type) && isset($request_data->misc_cost->value)) {
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
                        $misc_cost = $this->getDoctrine()->getRepository($this->get('constants')->database->MISC_COST_REPOSITORY)->find($misc_cost_id);
                        if (isset($misc_cost)) {
                            if ($misc_cost->getCreator()->getUsername() == $user->getUsername()) {
                                $misc_cost->setType($request_data->misc_cost->type)
                                    ->setValue($request_data->misc_cost->value);

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($misc_cost);
                                $em->flush();
                                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_MISC_COST_NOT_CREATOR;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_MISC_COST_DOES_NOT_EXIST;
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
     * Removes a miscellaneous cost from a specific vehicle
     *
     * @param string $license_plate_no
     * @param int $misc_cost_id
     * @return Response
     */
    public function removeAction($license_plate_no, $misc_cost_id) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $misc_cost = $this->getDoctrine()->getRepository($this->get('constants')->database->MISC_COST_REPOSITORY)->find($misc_cost_id);
            if (isset($misc_cost)) {
                $vehicle = $misc_cost->getVehicle();
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
                        if ($misc_cost->getCreator()->getUsername() == $user->getUsername()) {
                            $em = $this->getDoctrine()->getManager();
                            $em->remove($misc_cost);
                            $em->flush();
                            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_MISC_COST_NOT_CREATOR;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_MISC_COST_NOT_ASSIGNED_TO_VEHICLE;
                    }
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_MISC_COST_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
