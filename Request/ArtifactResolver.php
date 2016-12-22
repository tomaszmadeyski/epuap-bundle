<?php

namespace Madeyski\EpuapBundle\Request;

use Madeyski\EpuapBundle\Settings\CommonSignatureProvider;
use Madeyski\EpuapBundle\Settings\CommonSignatureProviderInterface;

class ArtifactResolver implements ArtifactResolverInterface
{
    /**
     * @var CommonSignatureProviderInterface
     */
    private $commonSignatureProvider;

    public function __construct(CommonSignatureProviderInterface $commonSignatureProvider)
    {
        $this->commonSignatureProvider = $commonSignatureProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($artifactEnc)
    {
        if (!$artifactEnc) {
            throw new \InvalidArgumentException('Missing SAMLart parameter');
        }

//        $artifact = base64_decode($artifactEnc);
//        $endpointIndex =  bin2hex(substr($artifact, 2, 2));
//        $sourceId = bin2hex(substr($artifact, 4, 20));

//        $metadataHandler = \SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
//
//        $idpMetadata = $metadataHandler->getMetaDataConfigForSha1($sourceId, 'saml20-idp-remote');

        $routesCollection = $this->commonSignatureProvider->getRoutesCollection();

        $ar = new \SAML2_ArtifactResolve();
        $ar->setArtifact($artifactEnc);
        $ar->setDestination($routesCollection[CommonSignatureProvider::ROUTE_ARTIFACT_RESOLVE]);
        $ar->setIssuer($this->commonSignatureProvider->getAppId());

        $publicKey = new \XMLSecurityKey(\XMLSecurityKey::RSA_1_5, array('type'=>'private'));
        $publicKey->loadKey($this->commonSignatureProvider->getPrivateCertificateValue());
        $ar->setSignatureKey($publicKey);
        $ar->setCertificates(array($this->commonSignatureProvider->getPubCertificateValue()));

        $ctxOpts = array(
            'ssl' => array(
                'capture_peer_cert' => true,
            ),
        );

        $context = stream_context_create($ctxOpts);

        $options = array(
            'uri' => $ar->getIssuer(),
            'location' => $ar->getDestination(),
            'stream_context' => $context,
        );

        $soapClient = new \SoapClient(null, $options);

        $request = $ar->toSignedXML();
        $request = \SAML2_SOAPClient::START_SOAP_ENVELOPE . $request->ownerDocument->saveXML($request) . \SAML2_SOAPClient::END_SOAP_ENVELOPE;

        $action = 'http://www.oasis-open.org/committees/security';
        $version = '1.1';
        $destination = $ar->getDestination();

        $soapresponsexml = $soapClient->__doRequest($request, $destination, $action, $version);

    }
}
