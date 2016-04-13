<?php

namespace CoreBundle\Util;

use CoreBundle\Util\Constants\Retriever;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;

/*
 * For handling the user login authentication
 *
 * hosted as a service : login_authenticator
 */
class LoginAuthenticator {
    private $session;
    private $constants;
    private $user_repo;

    /**
     * LoginAuthenticator constructor
     *
     * @param Session $session
     * @param EntityRepository $user_repo
     * @param Retriever $constants
     */
    public function __construct(Session $session, EntityRepository $user_repo, Retriever $constants) {
        $this->session = $session;
        $this->constants = $constants;
        $this->user_repo = $user_repo;
    }

    /**
     * Returns a boolean to indicate if the user had already logged into the system
     *
     * Returns true if the user had already logged in, the user exists in the database and the user is active
     * Returns false if user had not logged in or, the user does not exist in the database or the user is not active
     *
     * @return bool
     */
    public function authenticateUser() {
        // Checking if the user is logged into the system
        $username = $this->session->get($this->constants->session->USERNAME);
        if (isset($username)) {
            $user = $this->user_repo->find($username);
            if ($user) {
                if ($user->getActive()) {
                    return true;
                }
            }
        }

        // Destroys the session
        $this->session->invalidate();
        return false;
    }
}
