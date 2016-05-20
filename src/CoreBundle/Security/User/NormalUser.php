<?php

namespace AppBundle\Security\User;

use CoreBundle\Util\Constants\Retriever;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class NormalUser implements UserInterface, EquatableInterface {
    private $username;
    private $password;
    private $salt;
    private $roles;

    /**
     * NormalUser constructor
     *
     * @param $username
     * @param $password
     * @param $salt
     */
    public function __construct($username, $password, $salt) {
        $constants = new Retriever();

        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = array(
            $constants->security->USER_ROLE_NORMAL,
        );
    }

    /**
     * Returns if a $user object is equal to the current user object
     *
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user) {
        if ($user instanceof NormalUser) {
            if ($this->username !== $user->getUsername()) {
                if ($this->password !== $user->getPassword()) {

                }
            }
        }
        return false;
    }

    /**
     * Returns the roles granted to the user
     *
     * @return array (Role|string) The user roles
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user
     *
     * @return string The password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password
     *
     * @return string|null The salt
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user
     *
     * This is important if, at any given point, sensitive information is stored on this object
     */
    public function eraseCredentials() {
        $this->password = null;
    }
}
