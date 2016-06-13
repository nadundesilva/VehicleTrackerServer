<?php

namespace VehicleBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller {
    /**
     * Returns all the existing owned vehicles
     *
     * @return Response
     */
    public function getAllAction() {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $owned_vehicles = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->VEHICLE_REPOSITORY)->findBy(
                array('owner' => $user->getUsername()),
                array('name' => 'ASC')
            );
            $managed_vehicles = $user->getVehicle();

            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($owned_vehicles)) {
            $response_body[$this->get('constants')->response->OWNED_VEHICLES] = $owned_vehicles;
        }
        if(isset($managed_vehicles)) {
            $response_body[$this->get('constants')->response->MANAGED_VEHICLES] = $managed_vehicles;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * Returns a specific existing owned vehicles
     *
     * @param string $license_plate_no
     * @return Response
     */
    public function getAction($license_plate_no) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $owned_vehicle = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->VEHICLE_REPOSITORY)->findOneBy(
                array('owner' => $user->getUsername(), 'licensePlateNo' => $license_plate_no),
                array('name' => 'ASC')
            );

            $vehicle = null;
            if ($owned_vehicle != null) {
                $vehicle = $owned_vehicle;
            } else {
                $managed_vehicles = $user->getVehicle();
                $managed_vehicles_list_size = sizeof($managed_vehicles);
                for ($i = 0; $i < $managed_vehicles_list_size; $i++) {
                    if ($managed_vehicles[$i]->getLicensePlateNo() == $license_plate_no) {
                        $vehicle = $managed_vehicles[$i];
                        break;
                    }
                }
            }

            if ($vehicle != null) {
                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
            } else {
                $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($vehicle)) {
            $response_body[$this->get('constants')->response->VEHICLE] = $vehicle;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('view'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Returns all the names of all the existing vehicles
     *
     * @return Response
     */
    public function getAllNamesAction() {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $owned_vehicles = $this->getDoctrine()->getManager()
                ->createQuery('SELECT DISTINCT vehicle.licensePlateNo AS license_plate_no, vehicle.name AS name FROM VehicleBundle:Vehicle AS vehicle INNER JOIN vehicle.owner AS owner WHERE owner.username = :owner')
                ->setParameter('owner', $user->getUsername())
                ->getArrayResult();
            $managed_vehicles = $user->getVehicle();

            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($owned_vehicles)) {
            $response_body[$this->get('constants')->response->OWNED_VEHICLES] = $owned_vehicles;
        }
        if(isset($managed_vehicles)) {
            $response_body[$this->get('constants')->response->MANAGED_VEHICLES] = $managed_vehicles;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('names_list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Returns all the names of all the existing owned vehicles
     *
     * @return Response
     */
    public function getAllOwnedNamesAction() {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $owned_vehicles = $this->getDoctrine()->getManager()
                ->createQuery('SELECT DISTINCT vehicle.licensePlateNo AS license_plate_no, vehicle.name AS name FROM VehicleBundle:Vehicle AS vehicle INNER JOIN vehicle.owner AS owner WHERE owner.username = :owner')
                ->setParameter('owner', $user->getUsername())
                ->getArrayResult();

            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($owned_vehicles)) {
            $response_body[$this->get('constants')->response->OWNED_VEHICLES] = $owned_vehicles;
        }
        $response = new Response($this->get('jms_serializer')->serialize($response_body, 'json', SerializationContext::create()->setGroups(array('names_list'))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
