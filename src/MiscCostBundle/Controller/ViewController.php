<?php

namespace MiscCostBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller {
    /**
     * Returns all the miscellaneous costs
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
                $misc_costs = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->MISC_COST_REPOSITORY)->findBy(
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
        if(isset($misc_costs)) {
            $response_body[$this->get('constants')->response->MISC_COSTS] = $misc_costs;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Returns a specific existing miscellaneous costs
     *
     * @param string $license_plate_no
     * @param string $misc_cost_id
     * @return Response
     */
    public function getAction($license_plate_no, $misc_cost_id) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $misc_cost = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->MISC_COST_REPOSITORY)->findOneBy(
                array('vehicle' => $license_plate_no, 'id' => $misc_cost_id)
            );

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
                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_MISC_COST_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($misc_cost)) {
            $response_body[$this->get('constants')->response->MISC_COST] = $misc_cost;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('view'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
