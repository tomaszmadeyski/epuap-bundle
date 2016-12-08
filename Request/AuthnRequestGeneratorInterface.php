<?php

namespace Madeyski\EpuapBundle\Request;

/**
 * Interface AuthnRequestGeneratorInterface
 *
 * @package Madeyski\EpuapBundle\Request
 */
interface AuthnRequestGeneratorInterface
{
    /**
     * Method returns AuthnRequest xml string
     *
     * @return string
     */
    public function getAuthnRequest();

    /**
     * Method returns SAMLRequest token value
     *
     * @return string
     */
    public function getSamlRequest();
}
