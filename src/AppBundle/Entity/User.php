<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string
     *
     * @ORM\Column(name="authentication_token", type="string")
     */
    protected $authenticationToken;

    /**
     * Set authenticationToken
     *
     * @param string $authenticationToken
     *
     * @return User
     */
    public function setAuthenticationToken($authenticationToken)
    {
        $this->authenticationToken = $authenticationToken;

        return $this;
    }

    /**
     * Get authenticationToken
     *
     * @return string
     */
    public function getAuthenticationToken()
    {
        return $this->authenticationToken;
    }
}