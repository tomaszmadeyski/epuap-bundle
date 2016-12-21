<?php

namespace Madeyski\EpuapBundle\Controller;


use Madeyski\EpuapBundle\Security\Authentication\EpuapResponseConsumerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller provides action to consume SAML artifact from Epuap
 *
 * @package Madeyski\EpuapBundle\Controller
 * @author  Tomasz Madeyski <tomasz.madeyski@gmail.com>
 */
class ResponseConsumerController
{
    /**
     * @var EpuapResponseConsumerInterface
     */
    protected $epuapResponseConsumer;

    /**
     * ResponseConsumerController constructor.
     *
     * @param EpuapResponseConsumerInterface $epuapResponseConsumer
     */
    public function __construct(EpuapResponseConsumerInterface $epuapResponseConsumer)
    {
        $this->epuapResponseConsumer = $epuapResponseConsumer;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function consumeAction(Request $request)
    {
        $samlArt = $request->query->get('SAMLart', null);

        if (null === $samlArt) {
            throw new \InvalidArgumentException('Missing SAMLart parameter');
        }

        $this->epuapResponseConsumer->consumeArtifact($samlArt);

        return new Response('ok');
    }
}
