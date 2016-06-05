<?php

namespace MiscCostBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller {
    /**
     * Returns all the fuel fill ups
     *
     * @param string $license_plate_no
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
                $fuel_fill_ups = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->FUEL_FILL_UP_REPOSITORY)->findBy(
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
        if(isset($fuel_fill_ups)) {
            $response_body[$this->get('constants')->response->FUEL_FILL_UPS] = $fuel_fill_ups;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Returns a specific existing fuel fill ups
     *
     * @param string $license_plate_no
     * @param string $fuel_fill_up_id
     * @return Response
     */
    public function getAction($license_plate_no, $fuel_fill_up_id) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $fuel_fill_up = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->FUEL_FILL_UP_REPOSITORY)->findOneBy(
                array('vehicle' => $license_plate_no, 'id' => $fuel_fill_up_id)
            );

            if (isset($fuel_fill_up)) {
                $vehicle = $fuel_fill_up->getVehicle();
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
                $response_text = $this->get('constants')->response->STATUS_FUEL_FILL_UP_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($fuel_fill_up)) {
            $response_body[$this->get('constants')->response->FUEL_FILL_UP] = $fuel_fill_up;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('view'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
