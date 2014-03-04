<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractComponent as Component;
use OneLogin_Saml_Settings as SamlSettings;

class Util
{

    public static function createOneLoginSamlSettings(Component $component)
    {
        $settings = new SamlSettings();
        $settings->idpSingleSignOnUrl = $component->getConfigValue('idp_sso_url');
        $settings->idpPublicCertificate = $component->getConfigValue('idp_certificate');
        $settings->spIssuer = $component->getConfigValue('sp_issuer');
        $settings->spReturnUrl = $component->getConfigValue('sp_callback_url');

        if ($nameIdFormat = $component->getConfigValue('name_id_format')) {
            $settings->requestedNameIdFormat = constant('OneLogin_Saml_Settings::'.$nameIdFormat);
        }

        return $settings;
    }

}

