<?php

namespace Madeyski\EpuapBundle\Controller;

use Madeyski\EpuapBundle\Settings\CommonSignatureProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TestController
 *
 * @package Madeyski\EpuapBundle\Controller
 * @Route("/public/puap")
 */
class LoginController extends Controller
{
    /**
     * @Route("/authn")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authnRequest()
    {
        $samlRequest = $this->get('madeyski.puap.request.authn_request_generator')->getSamlRequest();
        $puapSettings = $this->getParameter('puap_settings');

        return $this->render('MadeyskiEpuapBundle:Login:authn.html.twig', array(
            'samlRequest' => $samlRequest,
            'signOnUrl' => $puapSettings[CommonSignatureProvider::KEY_URL_COLLECTION][CommonSignatureProvider::ROUTE_SINGLE_SIGN_ON],
        ));
    }

    /**
     * @Route("/login_comsume", name="puap_artifact_resolve")
     */
    public function artifactResolve(Request $request)
    {
        $artifactResolver = $this->get('madeyski.puap.artifact_resolver');

        $artifactResolver->resolve($request->query->get('SAMLart', null));

        return new Response('ok');
    }
}
