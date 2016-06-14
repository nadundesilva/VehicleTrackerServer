<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller {
    /**
     * Generate report for fuel fill up costs over a time period
     *
     * @param Request $request
     * @return Response
     */
    public function fuelConsumptionAction(Request $request) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->criteria) && isset($request_data->criteria->vehicle) && isset($request_data->criteria->vehicle->license_plate_no) && isset($request_data->criteria->vehicle->drivers) && isset($request_data->criteria->date) && isset($request_data->criteria->date->start_date) && isset($request_data->criteria->date->end_date)) {
                $drivers = $request_data->criteria->vehicle->drivers;

                $query = "SELECT CONCAT(driver.firstName, ' ', driver.lastName) AS name, YEAR(fillUp.timestamp) AS year, MONTH(fillUp.timestamp) AS month, fillUp.litres AS litres FROM FuelFillUpBundle:FillUp AS fillUp INNER JOIN fillUp.vehicle AS vehicle INNER JOIN vehicle.owner AS owner INNER JOIN vehicle.driver AS driver WHERE vehicle.owner = :username AND fillUp.timestamp > :start_date AND fillUp.timestamp < :end_date AND vehicle.licensePlateNo = :license_plate_no";
                if (sizeof($drivers) > 0) {
                    $query .= ' AND (';
                }
                for ($i = 0 ; $i < sizeof($drivers) ; $i++) {
                    if ($i != 0 ) {
                        $query .= ' OR';
                    }
                    $query .= ' driver.username = :driver' . $i;
                }
                if (sizeof($drivers) > 0) {
                    $query .= ') ';
                }
                $query .= ' GROUP BY driver, year, month';

                $result = $this->getDoctrine()->getManager()
                    ->createQuery($query)
                    ->setParameter('username', $user->getUsername())
                    ->setParameter('start_date', $request_data->criteria->date->start_date)
                    ->setParameter('end_date', $request_data->criteria->date->end_date)
                    ->setParameter('license_plate_no', $request_data->criteria->vehicle->license_plate_no);
                for ($i = 0 ; $i < sizeof($drivers) ; $i++) {
                    $result = $result->setParameter('driver' . $i, $drivers[$i]);
                }
                $result = $result->getArrayResult();

                $diff = strtotime($request_data->criteria->date->end_date) - strtotime($request_data->criteria->date->start_date);
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                if ($months > 12) {
                    $increment = 'YEAR';
                } else {
                    $increment = 'MONTH';
                }

                $series = [];
                $data = [];
                for ($i = 0 ; $i < sizeof($result) ; ) {
                    $series[] = $result[$i]['name'];
                    $series_data = [];
                    for ($time = strtotime($request_data->criteria->date->start_date) ; $time < strtotime($request_data->criteria->date->end_date) ; $time = strtotime("+1 month", $time)) {
                        if ($increment == 'MONTH') {
                            if ($i < sizeof($result) && ($result[$i]['month'] == date("F", $time) || $result[$i]['year'] == date("Y", $time))) {
                                $series_data[] = $result[$i]['litres'];
                                $i++;
                            } else {
                                $series_data[] = 0;
                            }
                        } else {
                            if ($i < sizeof($result) && ($result[$i]['year'] == date("Y", $time))) {
                                $series_data[] = $result[$i]['litres'];
                                $i++;
                            } else {
                                $series_data[] = 0;
                            }
                        }
                    }
                    $data[] = $series_data;
                }
                $labels = [];
                for ($time = strtotime($request_data->criteria->date->start_date) ; $time < strtotime($request_data->criteria->date->end_date) ; $time = strtotime("+1 month", $time)) {
                    if ($increment == 'MONTH') {
                        $labels[] = date("Y-m", $time);
                    } else {
                        $labels[] = date("Y", $time);
                    }
                }
                $report = array(
                    'series' => $series,
                    'labels' => $labels,
                    'data' => $data
                );

                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
            } else {
                $response_text = $this->get('constants')->response->STATUS_NO_ARGUMENTS_PROVIDED;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_NOT_LOGGED_IN;
        }

        $response_body = array($this->get('constants')->response->STATUS => $response_text);
        if(isset($report)) {
            $response_body[$this->get('constants')->response->REPORT] = $report;
        }
        $response = new Response(json_encode($response_body));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}

