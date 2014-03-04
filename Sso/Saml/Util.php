<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractComponent as Component;
use OneLogin_Saml_Settings as SamlSettings;

class Util
{

    const PEM_START = '-----BEGIN CERTIFICATE-----';
    const PEM_CLOSE = '-----END CERTIFICATE-----';

    public static function createOneLoginSamlSettings(Component $component)
    {
        $settings = new SamlSettings();
        $settings->idpSingleSignOnUrl = $component->getConfigValue('idp_sso_url');
        $settings->spIssuer = $component->getConfigValue('sp_issuer');
        $settings->spReturnUrl = $component->getConfigValue('sp_callback_url');

        $certificate = $component->getConfigValue('idp_certificate');
        $certificate = self::cleanupX509Certificate($certificate);
        $settings->idpPublicCertificate = $certificate;

        if ($nameIdFormat = $component->getConfigValue('name_id_format')) {
            $settings->requestedNameIdFormat = constant('OneLogin_Saml_Settings::'.$nameIdFormat);
        }

        return $settings;
    }

    private static function cleanupX509Certificate($str)
    {
        $certificate = trim($str);

        if (self::stringStartsWith($certificate, self::PEM_START)) {
            $certificate = substr($certificate, strlen(self::PEM_START));
        }

        if (self::stringEndsWith($certificate, self::PEM_CLOSE)) {
            $certLength = strlen($certificate);
            $certificate = substr($certificate, 0, $certLength - strlen(self::PEM_CLOSE));
        }

        $certificate = preg_replace('/\s+/', "\n", $certificate);
        $certificate = trim($certificate);

        return self::PEM_START."\n".$certificate."\n".self::PEM_CLOSE;
    }

    private static function stringStartsWith($str, $search)
    {
        if ('' === $search) {
            return true;
        }
        return 0 === strpos($str, $search);
    }

    private static function stringEndsWith($str, $search)
    {
        if ('' === $search) {
            return true;
        }
        return substr($str, -strlen($search)) === $search;
    }

}

