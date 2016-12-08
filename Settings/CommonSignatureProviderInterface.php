<?php

namespace Madeyski\EpuapBundle\Settings;


interface CommonSignatureProviderInterface
{
    /**
     * Metoda generuje unikalny identyfikator o dlugosci self::PUAP_CALL_ID_LENGTH
     *
     * @return string
     */
    public function getCallId();

    /**
     * Metoda generuje string z timestampem w formacie oczekiwanym przez ePuap
     *
     * @return string
     */
    public function getTimestampString();

    /**
     * Metoda zwraca identyfikator aplikacji w systemie ePuap
     *
     * @return string
     */
    public function getAppId();

    /**
     * Metoda zwraca wartosc klucza publicznego
     *
     * @return string
     */
    public function getPubCertificateValue();

    /**
     * Metoda zwraca wartosc klucza prywatnego
     *
     * @return string
     */
    public function getPrivateCertificateValue();

    /**
     * Metoda zwraca adresy url uslug sieciowych puap
     *
     * @return array
     */
    public function getRoutesCollection();

}
