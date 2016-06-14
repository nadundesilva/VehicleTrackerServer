<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller {
    /**
     * Generate report for miscellaneous costs over a time period for a vehicle
     *
     * @param Request $request
     * @return Response
     */
    public function miscCostAction(Request $request) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->criteria) && isset($request_data->criteria->vehicle) && isset($request_data->criteria->vehicle->license_plate_no) && isset($request_data->criteria->date) && isset($request_data->criteria->date->start_date) && isset($request_data->criteria->date->end_date)) {
                $query = 'SELECT miscCost.type AS type, miscCost.value AS value FROM MiscCostBundle:MiscCost AS miscCost INNER JOIN miscCost.vehicle AS vehicle INNER JOIN vehicle.owner AS owner WHERE vehicle.owner = :username AND miscCost.timestamp > :start_date AND miscCost.timestamp < :end_date AND vehicle.licensePlateNo = :license_plate_no GROUP BY type';
                $result = $this->getDoctrine()->getManager()
                    ->createQuery($query)
                    ->setParameter('username', $user->getUsername())
                    ->setParameter('start_date', $request_data->criteria->date->start_date)
                    ->setParameter('end_date', $request_data->criteria->date->end_date)
                    ->setParameter('license_plate_no', $request_data->criteria->vehicle->license_plate_no);
                $result = $result->getArrayResult();

                $labels = [];
                $data = [];
                for ($i = 0 ; $i < sizeof($result) ; $i++) {
                    $labels[] = $result[$i]['type'];
                    $data[] = $result[$i]['value'];
                }
                $report = array(
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
