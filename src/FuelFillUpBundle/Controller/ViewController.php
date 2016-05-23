<?php

namespace FuelFillUpBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller {
    /**
     * Returns all the check ins
     *
     * @param $license_plate_no
     * @return Response
     */
    public function getAllAction($license_plate_no) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $vehicle = $this->getDoctrine()->getRepository($this->get('constants')->database->VEHICLE_REPOSITORY)->find($license_plate_no);
            $driver_list = $vehicle->getDriver();
            $driver_list_size = sizeof($driver_list);
            for ($i = 0; $i < $driver_list_size; $i++) {
                if ($driver_list[$i]->getUsername() == $user->getUsername()) {
                    $is_a_driver = true;
                    break;
                }
            }
            if (isset($is_a_driver) || $vehicle->getOwner()->getUsername() == $user->getUsername()) {
                $check_ins = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->CHECK_IN_REPOSITORY)->findBy(
                    array('creator' => $user->getUsername(), 'vehicle' => $license_plate_no)
                );

                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
            } else {
                $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($check_ins)) {
            $response_body[$this->get('constants')->response->CHECK_INS] = $check_ins;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Returns a specific existing check in
     *
     * @param string $license_plate_no
     * @param $check_in_id
     * @return Response
     */
    public function getAction($license_plate_no, $check_in_id) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $check_in = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->CHECK_IN_REPOSITORY)->findOneBy(
                array('vehicle' => $license_plate_no, 'id' => $check_in_id)
            );

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
                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_CHECK_IN_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($check_in)) {
            $response_body[$this->get('constants')->response->CHECK_IN] = $check_in;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('view'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
