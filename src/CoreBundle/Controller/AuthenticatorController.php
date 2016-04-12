<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatorController extends Controller {
    public function registerAction() {
        $request = Request::createFromGlobals()->request;
        $username = $request->get($this->get('constants')->request->USERNAME);
        $first_name = $request->get($this->get('constants')->request->FIRST_NAME);
        $last_name = $request->get($this->get('constants')->request->LAST_NAME);
        $password = $request->get($this->get('constants')->request->PASSWORD);

        if (!$this->get('login_authenticator')->authenticateUser()) {
            if (isset($username) && isset($first_name) && isset($last_name) && isset($password)) {
                $user = new User();
                $user->setUsername($username)
                    ->setFirstName($first_name)
                    ->setLastName($last_name)
                    ->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $response_text = $this->get('constants')->response->STATUS_SUCCESS;
            } else {
                $response_text = $this->get('constants')->response->STATUS_NO_ARGUMENTS_PROVIDED;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_ALREADY_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function loginAction() {
        $request = json_decode(Request::createFromGlobals()->getContent());
        $username = $request->username;
        $password = $request->password;

        if (isset($username) && isset($password)) {
            $user = $this->getDoctrine()->getRepository($this->get('constants')->database->USER_REPOSITORY)->find($username);
            if ($user) {
                if ($user->getPassword() == $password) {
                    $this->get('session')->set($this->get('constants')->session->USERNAME, $user->getUsername());
                    $this->get('session')->set($this->get('constants')->session->FIRST_NAME, $user->getFirstName());
                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                } else {
                    $response_text = $this->get('constants')->response->STATUS_WRONG_PASSWORD;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_NOT_REGISTERED;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_NO_ARGUMENTS_PROVIDED;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function logoutAction() {
        $this->get('session')->invalidate();

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $this->get('constants')->response->STATUS_SUCCESS)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
