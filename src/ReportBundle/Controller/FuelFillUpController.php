<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FuelFillUpController extends Controller {
    /**
     * Adds a new miscellaneous cost for a specific vehicle
     *
     * @param Request $request
     * @return Response
     */
    public function mileageAction(Request $request) {
        $request_data = json_decode($request->getContent());
        if ($user = $this->get('login_authenticator')->authenticateUser()) {
            if (isset($request_data) && isset($request_data->report_criteria)) {
                $query = $this->getDoctrine()->getManager()
                    ->createQuery('SELECT ');
            } else {
                $query = $this->getDoctrine()->getManager()
                    ->createQuery('SELECT ');
            }
            $report = $query->getArrayResult();
            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
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
