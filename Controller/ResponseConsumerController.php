<?php

namespace Madeyski\EpuapBundle\Controller;


use Madeyski\EpuapBundle\Security\Authentication\EpuapResponseConsumerInterface;
use Madeyski\EpuapBundle\Settings\CommonSignatureProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\RouterInterface;

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
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $epuapSettings;

    /**
     * ResponseConsumerController constructor.
     *
     * @param EpuapResponseConsumerInterface $epuapResponseConsumer
     * @param RouterInterface                $router
     * @param array                          $epuapSettings
     */
    public function __construct(EpuapResponseConsumerInterface $epuapResponseConsumer, RouterInterface $router, array $epuapSettings)
    {
        $this->epuapResponseConsumer = $epuapResponseConsumer;
        $this->router = $router;
        $this->epuapSettings = $epuapSettings;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function consumeAction(Request $request)
    {
        $samlArt = $request->query->get('SAMLart', null);

        if (null === $samlArt) {
            throw new \InvalidArgumentException('Missing SAMLart parameter');
        }

        $success = $this->epuapResponseConsumer->consumeArtifact($samlArt);

        if ($success) {
            $redirectUrl = $this->epuapSettings[CommonSignatureProvider::KEY_URL_COLLECTION][CommonSignatureProvider::ROUTE_POST_LOGIN_REDIRECT];

            if ('@' === substr($redirectUrl, 0, 1)) {
                $redirectUrl = $this->router->generate(substr($redirectUrl, 1));
            }

            return new RedirectResponse($redirectUrl);
        }

        throw new AccessDeniedHttpException();
    }
}
