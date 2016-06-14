<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiscCostController extends Controller {
    /**
     * Generate report for miscellaneous costs over a time period
     *
     * @param Request $request
     * @return Response
     */
    public function aggregateMiscCostAction(Request $request) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->criteria) && isset($request_data->criteria->vehicles) && isset($request_data->criteria->date) && isset($request_data->criteria->date->start_date) && isset($request_data->criteria->date->end_date)) {
                $vehicles = $request_data->criteria->vehicles;

                $query = 'SELECT vehicle.licensePlateNo AS license_plate_no, vehicle.name AS name, YEAR(miscCost.timestamp) AS year, MONTH(miscCost.timestamp) AS month, miscCost.value AS value FROM MiscCostBundle:MiscCost AS miscCost INNER JOIN miscCost.vehicle AS vehicle INNER JOIN vehicle.owner AS owner WHERE vehicle.owner = :username AND miscCost.timestamp > :start_date AND miscCost.timestamp < :end_date';
                if (sizeof($vehicles) > 0) {
                    $query .= ' AND (';
                }
                for ($i = 0 ; $i < sizeof($vehicles) ; $i++) {
                    if ($i != 0 ) {
                        $query .= ' OR';
                    }
                    $query .= ' vehicle.licensePlateNo = :license_plate_no' . $i;
                }
                if (sizeof($vehicles) > 0) {
                    $query .= ') ';
                }
                $query .= ' GROUP BY license_plate_no, year, month';

                $result = $this->getDoctrine()->getManager()
                    ->createQuery($query)
                    ->setParameter('username', $user->getUsername())
                    ->setParameter('start_date', $request_data->criteria->date->start_date)
                    ->setParameter('end_date', $request_data->criteria->date->end_date);
                for ($i = 0 ; $i < sizeof($vehicles) ; $i++) {
                    $result = $result->setParameter('license_plate_no' . $i, $vehicles[$i]);
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
                                $series_data[] = $result[$i]['value'];
                                $i++;
                            } else {
                                $series_data[] = 0;
                            }
                        } else {
                            if ($i < sizeof($result) && ($result[$i]['year'] == date("Y", $time))) {
                                $series_data[] = $result[$i]['value'];
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
