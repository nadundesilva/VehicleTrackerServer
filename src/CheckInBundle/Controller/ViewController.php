<?php

namespace CheckInBundle\Controller;

use CoreBundle\Util\Constants\Retriever;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller {
    /**
     * Returns all the check ins
     *
     * @return Response
     */
    public function getAllAction($license_plate_no) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $check_ins = $this->getDoctrine()->getManager()->getRepository((new Retriever())->database->CHECK_IN_REPOSITORY)->findBy(
                array('creator' => $user->getUsername(), 'vehicle' => $license_plate_no)
            );

            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
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
            $owned_vehicles_list_size = sizeof($owned_vehicles);
            for ($i = 0; $i < $owned_vehicles_list_size; $i++) {
                if ($owned_vehicles[$i]['license_plate_no'] == $license_plate_no) {
                    $vehicle = $owned_vehicles[$i];
                    break;
                }
            }
            if ($vehicle === null) {
                $managed_vehicles_list_size = sizeof($managed_vehicles);
                for ($i = 0; $i < $managed_vehicles_list_size; $i++) {
                    if ($managed_vehicles[$i]->getLicensePlateNo() == $license_plate_no) {
                        $vehicle = $managed_vehicles[$i];
                        break;
                    }
                }
            }

            if ($vehicle !== null) {
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
}
