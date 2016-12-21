<?php

namespace Madeyski\EpuapBundle\Security\Authentication;

/**
 * Interface for classes being able to consume SAML artifact
 *
 * @package Madeyski\EpuapBundle\Security\Authentication
 * @author  Tomasz Madeyski <tomasz.madeyski@gmail.com>
 */
interface EpuapResponseConsumerInterface
{
    /**
     * @param string $samlArtifact
     *
     * @return bool Return true if success, false otherwise
     */
    public function consumeArtifact($samlArtifact);
}
