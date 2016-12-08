<?php

namespace Madeyski\EpuapBundle\Settings;


class CommonSignatureProvider implements CommonSignatureProviderInterface
{
    const PUAP_CALL_ID_LENGHT = 17;

    const KEY_APP_ID = 'app_id';
    const KEY_PUB_KEY_PATH = 'security.pub_key_path';
    const KEY_PRIVATE_KEY_PATH = 'security.private_key_path';
    const KEY_SINGLE_SIGN_ON_SERVICE = 'url.single_sign_on';
    const KEY_ARTIFACT_RESOLVE = 'url.artifact_resolve';

    const ROUTE_SINGLE_SIGN_ON = 'single_sign_on';
    const ROUTE_ARTIFACT_RESOLVE = 'artifact_resolve';

    /**
     * @var string
     */
    protected $publicKeyPath;

    /**
     * @var string
     */
    protected $privateKeyPath;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var array
     */
    protected $routesCollection;

    /**
     * CommonSignatureProvider constructor.
     *
     * @param array $puapParams
     */
    public function __construct(array $puapParams)
    {
        if (!file_exists($puapParams[self::KEY_PUB_KEY_PATH])) {
            throw new \InvalidArgumentException(sprintf('Plik klucza publicznego o podanej sciezce (%s) nie istnieje', $puapParams[self::KEY_PUB_KEY_PATH]));
        }

        $this->publicKeyPath = $puapParams[self::KEY_PUB_KEY_PATH];

        if (!file_exists($puapParams[self::KEY_PRIVATE_KEY_PATH])) {
            throw new \InvalidArgumentException(sprintf('Plik klucza prywatnego o podanej sciezce (%s) nie istnieje', $puapParams[self::KEY_PRIVATE_KEY_PATH]));
        }

        $this->privateKeyPath = $puapParams[self::KEY_PRIVATE_KEY_PATH];

        if (is_null($puapParams[self::KEY_APP_ID])) {
            throw new \InvalidArgumentException('Nie podano wartosci puap.settings -> app_id');
        }

        $this->appId = $puapParams[self::KEY_APP_ID];
        $this->routesCollection = array(
            self::ROUTE_SINGLE_SIGN_ON => $puapParams[self::KEY_SINGLE_SIGN_ON_SERVICE],
            self::ROUTE_ARTIFACT_RESOLVE => $puapParams[self::KEY_ARTIFACT_RESOLVE],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCallId()
    {
        $number = '';

        for ($i=0; $i < self::PUAP_CALL_ID_LENGHT; $i++) {
            $min = ($i == 0) ? 1 : 0;
            $number .= mt_rand($min, 9);
        }

        return 'ID_' . $number;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestampString()
    {
        return strftime("%Y-%m-%dT%H:%M:%SZ");
    }

    /**
     * Metoda zwraca identyfikator aplikacji w systemie ePuap
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * {@inheritdoc}
     */
    public function getPubCertificateValue()
    {
        return file_get_contents($this->publicKeyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivateCertificateValue()
    {
        return file_get_contents($this->privateKeyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutesCollection()
    {
        return $this->routesCollection;
    }
}
