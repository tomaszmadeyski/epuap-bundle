<?php

namespace Madeyski\EpuapBundle\Security\Authentication\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * An Event class thrown on successfull puap login
 *
 * @package Madeyski\EpuapBundle\Security\Authentication\Event
 * @author  Tomasz Madeyski <tomasz.madeyski@gmail.com>
 */
class EpuapLoginEvent extends Event
{
    /**
     * @var string
     */
    protected $userName;

    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Method returns username obtained from Epuap
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }
}
