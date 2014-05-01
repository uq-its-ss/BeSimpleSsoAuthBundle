<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractComponent as Component;
use OneLogin_Saml2_Settings as Saml2Settings;
use OneLogin_Saml2_Constants as Saml2Constants;
use OneLogin_Saml2_Utils as Saml2Utils;

class Util
{

    public static function createOneLoginSamlSettings(Component $component)
    {
        $settings = array(
            'strict' => true,
            'debug' => false,
            'sp' => array(
                'entityId' => $component->getConfigValue('sp_issuer'),
                'assertionConsumerService' => array(
                    'url' => $component->getConfigValue('sp_callback_url'),
                    'binding' => Saml2Constants::BINDING_HTTP_POST,
                ),
                'nameIdFormat' => Saml2Constants::NAMEID_EMAIL_ADDRESS,
            ),
            'idp' => array(
                'entityId' => $component->getConfigValue('idp_entity_id'),
                'singleSignOnService' => array(
                    'url' => $component->getConfigValue('idp_sso_url'),
                    'binding' => Saml2Constants::BINDING_HTTP_REDIRECT,
                ),
                'x509cert' => '',
            ),
        );

        $certificate = $component->getConfigValue('idp_certificate');
        $certificate = Saml2Utils::formatCert($certificate, true);
        $settings['idp']['x509cert'] = $certificate;

        if ($nameIdFormat = $component->getConfigValue('name_id_format')) {
            $constantName = 'OneLogin_Saml2_Constants::'.$nameIdFormat;
            if (defined($constantName)) {
                $settings['sp']['nameIdFormat'] = constant($constantName);
            } else {
                $settings['sp']['nameIdFormat'] = $nameIdFormat;
            }
        }

        return new Saml2Settings($settings);
    }

}

