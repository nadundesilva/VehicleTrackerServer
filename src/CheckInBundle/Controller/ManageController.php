<?php

namespace CheckInBundle\Controller;

use CheckInBundle\Entity\CheckIn;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManageController extends Controller {
    /**
     * Adds a new checkIn or updates a checkIn for a specific vehicle
     *
     * if an add request had been sent a new check in will be added to the vehicle specified
     *
     * if an update request had been sent a the check in description will be updated
     *
     * @param Request $request
     * @param string $license_plate_no
     * @param string $check_in_id
     * @return Response
     */
    public function createUpdateAction(Request $request, $license_plate_no, $check_in_id = null) {
        $request_data = json_decode($request->getContent());

        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->check_in) && isset($request_data->check_in->description) && ($request->isMethod('PUT') || (isset($request_data->check_in->latitude) && isset($request_data->check_in->longitude)))) {
                if ($license_plate_no != null) {
                    $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
                }
                if (isset($vehicle)) {
                    $driver_list = $vehicle->getDriver();
                    for ($i = 0; $i < sizeof($driver_list); $i++) {
                        if ($driver_list[$i]->getUsername() == $user->getUsername()) {
                            $is_a_driver = true;
                            break;
                        }
                    }
                    if (isset($is_a_driver) || $vehicle->getOwner()->getUsername() == $user->getUsername()) {
                        $description = $request_data->check_in->description;
                        if ($request->isMethod('POST')) {
                            $check_in = (new CheckIn())
                                ->setLat($request_data->check_in->latitude)
                                ->setLong($request_data->check_in->longitude);
                        } else {
                            $check_in = $this->getDoctrine()->getRepository($this->get('constants')->database->CHECK_IN_REPOSITORY)->find($check_in_id);
                        }
                        if (isset($check_in)) {
                            if ($request->isMethod('POST') || ($check_in->getCreator()->getUsername() == $user->getUsername())) {
                                $check_in->setDescription($description)
                                    ->setTimestamp(new \DateTime());

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($vehicle);
                                $em->flush();
                                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_CHECK_IN_NOT_CREATOR_OR_OWNER_OF_VEHICLE;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_CHECK_IN_DOES_NOT_EXIST;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_A_DRIVER_OR_OWNER;
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
