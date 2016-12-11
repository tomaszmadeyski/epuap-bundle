<?php

namespace Madeyski\EpuapBundle\Request;

use Madeyski\EpuapBundle\Settings\CommonSignatureProvider;
use Madeyski\EpuapBundle\Settings\CommonSignatureProviderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AuthnRequestGenerator implements AuthnRequestGeneratorInterface
{
    /**
     * @var CommonSignatureProviderInterface
     */
    protected $commonSignatureProvider;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * AuthnRequestGenerator constructor.
     *
     * @param RouterInterface                  $router
     * @param CommonSignatureProviderInterface $commonSignatureProvider
     */
    public function __construct(RouterInterface $router, CommonSignatureProviderInterface $commonSignatureProvider)
    {
        $this->commonSignatureProvider = $commonSignatureProvider;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthnRequest()
    {
        $routesCollection = $this->commonSignatureProvider->getRoutesCollection();

        $authnRequest = new \SAML2_AuthnRequest();
        $authnRequest->setIssuer($this->commonSignatureProvider->getAppId());
        $authnRequest->setAssertionConsumerServiceURL($this->router->generate('puap_artifact_resolve', array(), UrlGeneratorInterface::ABSOLUTE_URL));
        $authnRequest->setDestination($routesCollection[CommonSignatureProvider::ROUTE_SINGLE_SIGN_ON]);

        $privateKey = new \XMLSecurityKey(\XMLSecurityKey::RSA_1_5, array('type'=>'private'));
        $privateKey->loadKey($this->commonSignatureProvider->getPrivateCertificateValue());

        $authnRequest->setSignatureKey($privateKey);
        $authnRequest->setProtocolBinding(\SAML2_Const::BINDING_HTTP_ARTIFACT);
        $authnRequest->setCertificates(array($this->commonSignatureProvider->getPubCertificateValue()));

        return $authnRequest->toSignedXML()->ownerDocument->saveXML();
    }

    /**
     * {@inheritdoc}
     */
    public function getSamlRequest()
    {
        return base64_encode($this->getAuthnRequest());
    }
}
