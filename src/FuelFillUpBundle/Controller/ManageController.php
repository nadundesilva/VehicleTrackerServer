<?php

namespace FuelFillUpBundle\Controller;

use FuelFillUpBundle\Entity\FillUp;
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
            if (isset($request_data) && isset($request_data->fuel_fill_up) && isset($request_data->fuel_fill_up->odo_meter_reading) && isset($request_data->fuel_fill_up->litres) && isset($request_data->fuel_fill_up->price) && isset($request_data->fuel_fill_up->station_latitude) && isset($request_data->fuel_fill_up->station_longitude)) {
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
                        $fuel_fill_up = (new FillUp())
                            ->setOdoMeterReading($request_data->fuel_fill_up->odo_meter_reading)
                            ->setLitres($request_data->fuel_fill_up->litres)
                            ->setPrice($request_data->fuel_fill_up->price)
                            ->setStationLat($request_data->fuel_fill_up->station_latitude)
                            ->setStationLong($request_data->fuel_fill_up->station_longitude)
                            ->setTimestamp(new \DateTime())
                            ->setVehicle($vehicle)
                            ->setCreator($user);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($fuel_fill_up);
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
     * @param int $fuel_fill_up_id
     * @return Response
     */
    public function updateAction(Request $request, $license_plate_no, $fuel_fill_up_id) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->fuel_fill_up) && isset($request_data->fuel_fill_up->odo_meter_reading) && isset($request_data->fuel_fill_up->litres) && isset($request_data->fuel_fill_up->price)) {
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
                        $fuel_fill_up = $this->getDoctrine()->getRepository($this->get('constants')->database->FUEL_FILL_UP_REPOSITORY)->find($fuel_fill_up_id);
                        if (isset($fuel_fill_up)) {
                            if ($fuel_fill_up->getCreator()->getUsername() == $user->getUsername()) {
                                $fuel_fill_up->setOdoMeterReading($request_data->fuel_fill_up->odo_meter_reading)
                                    ->setLitres($request_data->fuel_fill_up->litres)
                                    ->setPrice($request_data->fuel_fill_up->price);

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($vehicle);
                                $em->flush();
                                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                            } else {
                                $response_text = $this->get('constants')->response->STATUS_FUEL_FILL_UP_NOT_CREATOR;
                            }
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_FUEL_FILL_UP_DOES_NOT_EXIST;
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
     * @param int $fuel_fill_up_id
     * @return Response
     */
    public function removeAction($license_plate_no, $fuel_fill_up_id) {
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            $fuel_fill_up = $this->getDoctrine()->getRepository($this->get('constants')->database->FUEL_FILL_UP_REPOSITORY)->find($fuel_fill_up_id);
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
                    if ($vehicle->getLicensePlateNo() == $license_plate_no) {
                        if ($fuel_fill_up->getCreator()->getUsername() == $user->getUsername()) {
                            $em = $this->getDoctrine()->getManager();
                            $em->remove($fuel_fill_up);
                            $em->flush();
                            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_FUEL_FILL_UP_NOT_CREATOR;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_FUEL_FILL_UP_NOT_ASSIGNED_TO_VEHICLE;
                    }
                } else {
                    $response_text = $this->get('constants')->response->STATUS_VEHICLE_NOT_DRIVER_OR_OWNER;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_FUEL_FILL_UP_DOES_NOT_EXIST;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
