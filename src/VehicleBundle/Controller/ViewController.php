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
            $vehicle_repository = $this->getDoctrine()
                ->getManager()
                ->getRepository((new Retriever())->database->VEHICLE_REPOSITORY);
            $vehicle_query = $vehicle_repository->createQueryBuilder('vehicle')
                ->select(array('vehicle.licensePlateNo AS license_plate_no', 'vehicle.name', 'vehicle.make', 'vehicle.model', 'vehicle.year'))
                ->where('vehicle.owner = :username')
                ->setParameter('username', $user->getUsername())
                ->orderBy('vehicle.name', 'ASC')
                ->getQuery();
            $owned_vehicles = $vehicle_query->getResult();

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
            $vehicle_repository = $this->getDoctrine()
                ->getManager()
                ->getRepository((new Retriever())->database->VEHICLE_REPOSITORY);
            $vehicle_query = $vehicle_repository->createQueryBuilder('vehicle')
                ->select(array('vehicle.licensePlateNo AS license_plate_no', 'vehicle.name', 'vehicle.make', 'vehicle.model', 'vehicle.year', 'vehicle.fuelOne AS fuel_one', 'vehicle.fuelTwo AS fuel_two', 'vehicle.description'))
                ->where('vehicle.owner = :username')
                ->setParameter('username', $user->getUsername())
                ->orderBy('vehicle.name', 'ASC')
                ->getQuery();
            $owned_vehicles = $vehicle_query->getResult();
            $managed_vehicles = $user->getVehicle();

            $vehicle = null;
            for ($i = 0; $i < sizeof($owned_vehicles); $i++) {
                if ($owned_vehicles[$i]['license_plate_no'] == $license_plate_no) {
                    $vehicle = $owned_vehicles[$i];
                    break;
                }
            }
            if ($vehicle == null) {
                for ($i = 0; $i < sizeof($managed_vehicles); $i++) {
                    if ($managed_vehicles[$i]->getLicensePlateNo() == $license_plate_no) {
                        $vehicle = $managed_vehicles[$i];
                        break;
                    }
                }
            }

            if ($vehicle != null) {
                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
            } else {
                $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_A_DRIVER_OR_OWNER;
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
}
