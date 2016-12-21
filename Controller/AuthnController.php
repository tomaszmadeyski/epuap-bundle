<?php

namespace Madeyski\EpuapBundle\Controller;


use Madeyski\EpuapBundle\Request\AuthnRequestGeneratorInterface;
use Madeyski\EpuapBundle\Settings\CommonSignatureProvider;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Controller providing action to send authnRequest
 *
 * @package Madeyski\EpuapBundle\Controller
 * @author  Tomasz Madeyski <tomasz.madeyski@gmail.com>
 */
class AuthnController
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var AuthnRequestGeneratorInterface
     */
    protected $authnRequestGenerator;

    /**
     * @var array
     */
    protected $epuapSettings;

    /**
     * AuthnController constructor.
     *
     * @param EngineInterface                $templating
     * @param AuthnRequestGeneratorInterface $authnRequestGenerator
     * @param array                          $epuapSettings
     */
    public function __construct(EngineInterface $templating, AuthnRequestGeneratorInterface $authnRequestGenerator, array $epuapSettings)
    {
        $this->templating = $templating;
        $this->authnRequestGenerator = $authnRequestGenerator;
        $this->epuapSettings = $epuapSettings;
    }

    /**
     * Action sends authnRequest to ePuap
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authnRequestAction()
    {
        $samlRequest = $this->authnRequestGenerator->getSamlRequest();

        return $this->templating->renderResponse('MadeyskiEpuapBundle:Login:authn.html.twig', array(
            'samlRequest' => $samlRequest,
            'signOnUrl' => $this->epuapSettings[CommonSignatureProvider::KEY_URL_COLLECTION][CommonSignatureProvider::ROUTE_SINGLE_SIGN_ON],
        ));
    }
}
