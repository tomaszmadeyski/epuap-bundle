<?php

namespace Madeyski\EpuapBundle\Request;

/**
 * Interface for classes which resolve samlArtifacts
 *
 * @package Madeyski\EpuapBundle\Request
 * @author  Tomasz Madeyski <tomasz.madeyski@gmail.com>
 */
interface ArtifactResolverInterface
{
    /**
     * Method takes saml artifact, tries to resolve it and returns username on success
     *
     * @param string $samlArt
     *
     * @return string
     */
    public function resolve($samlArt);
}
