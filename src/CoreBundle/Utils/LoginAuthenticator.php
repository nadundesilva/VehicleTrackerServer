<?php

namespace CoreBundle\Utils;

use CoreBundle\Utils\Constants\Retriever;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginAuthenticator {
    private $session;
    private $constants;
    private $user_repo;

    public function __construct(Session $session, EntityRepository $user_repo, Retriever $constants) {
        $this->session = $session;
        $this->constants = $constants;
        $this->user_repo = $user_repo;
    }

    public function authenticateUser() {
        $username = $this->session->get($this->constants->session->USERNAME);
        if (isset($username)) {
            $user = $this->user_repo->find($username);
            if ($user) {
                if ($user->getActive()) {
                    return true;
                }
            }
        }

        $this->session->invalidate();
        return false;
    }
}
