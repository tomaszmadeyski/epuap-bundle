<?php

namespace Madeyski\EpuapBundle\Security\Authentication;


use Madeyski\EpuapBundle\Request\ArtifactResolverInterface;
use Madeyski\EpuapBundle\Security\Authentication\Event\EpuapLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class responsible for consuming saml artifact
 *
 * @package Madeyski\EpuapBundle\Security\Authentication
 * @author  Tomasz Madeyski <tomasz.madeyski@gmail.com>
 */
class EpuapResonseConsumer implements EpuapResponseConsumerInterface
{
    /**
     * @var ArtifactResolverInterface
     */
    protected $artifactResolver;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * EpuapResonseConsumer constructor.
     *
     * @param ArtifactResolverInterface $artifactResolver
     * @param EventDispatcherInterface  $eventDispatcher
     */
    public function __construct(ArtifactResolverInterface $artifactResolver, EventDispatcherInterface $eventDispatcher)
    {
        $this->artifactResolver = $artifactResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function consumeArtifact($samlArtifact)
    {
        $userName = $this->artifactResolver->resolve($samlArtifact);

        $puapLoginEvent = new EpuapLoginEvent($userName);
        $this->eventDispatcher->dispatch('epuap_login', $puapLoginEvent);

        return true;
    }
}
