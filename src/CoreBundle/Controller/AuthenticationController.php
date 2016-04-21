<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/*
 * For handling core authentication functionalities
 *
 * sign up
 * login
 * logout
 */
class AuthenticationController extends Controller {
    /**
     * Inserts a user into the database and returns the status as a string
     *
     * A new user will be created if the user had not already logged in
     * The username and the email provided must be unique
     * The passwords will be hashed before storing
     * The last login time will be set to current time
     * The user will be deactivated by default
     * To activate the user the user must verify the account using the email sent to the email address provided
     *
     * @param Request $request
     * @return Response
     */
    public function signUpAction(Request $request) {
        $request = json_decode($request->getContent());

        if (!$this->get('login_authenticator')->authenticateUser()) {
            if (isset($request)) {
                $username = $request->user->username;
                $first_name = $request->user->first_name;
                $last_name = $request->user->last_name;
                $password = $request->user->password;
                $email = $request->user->email;
                $user = new User();
                $user->setUsername($username)
                    ->setFirstName($first_name)
                    ->setLastName($last_name)
                    ->setEmail($email)
                    ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                    ->setLastLoginTime(new \DateTime());

                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $response_text = $this->get('constants')->response->STATUS_SUCCESS;

                    //TODO : Create an email to be sent to verify account
                } catch (UniqueConstraintViolationException $e) {
                    $message = $e->getMessage();
                    if (strpos($message, 'user.username')) {
                        $response_text = $this->get('constants')->response->STATUS_USER_DUPLICATE_USERNAME;
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_USER_DUPLICATE_EMAIL;
                    }
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_NO_ARGUMENTS_PROVIDED;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_USER_ALREADY_LOGGED_IN;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Logs in the user and returns the status as a string
     *
     * The session will be set if the hashed password match the password of the retrieved from the database
     * The last login time will be set to the current time
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        $request_data = json_decode($request->getContent());

        if (isset($request_data)) {
            $username = $request_data->user->username;
            $password = $request_data->user->password;
            $user = $this->getDoctrine()->getRepository($this->get('constants')->database->USER_REPOSITORY)->find($username);
            if ($user) {
                if (password_verify($password, $user->getPassword())) {
                    if ($user->getActive()) {
                        if ($user->getVerified()) {
                            $user->setLastLoginTime(new \DateTime());
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($user);
                            $em->flush();

                            $this->get('session')->set($this->get('constants')->session->USERNAME, $user->getUsername());
                            $response_text = $this->get('constants')->response->STATUS_SUCCESS;
                        } else {
                            $response_text = $this->get('constants')->response->STATUS_USER_NOT_VERIFIED;
                        }
                    } else {
                        $response_text = $this->get('constants')->response->STATUS_USER_NOT_ACTIVE;
                    }
                } else {
                    $response_text = $this->get('constants')->response->STATUS_USER_WRONG_PASSWORD;
                }
            } else {
                $response_text = $this->get('constants')->response->STATUS_USER_NOT_REGISTERED;
            }
        } else {
            $response_text = $this->get('constants')->response->STATUS_NO_ARGUMENTS_PROVIDED;
        }

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $response_text)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Logs out the user and returns the status as a string
     *
     * The session will be destroyed
     *
     * @return Response
     */
    public function logoutAction() {
        $this->get('session')->invalidate();

        $response = new Response(json_encode(array($this->get('constants')->response->STATUS => $this->get('constants')->response->STATUS_SUCCESS)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
