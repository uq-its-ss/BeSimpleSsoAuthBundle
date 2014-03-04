<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractServer;
use BeSimple\SsoAuthBundle\Sso\ServerInterface;
use Buzz\Message\Request;
use OneLogin_Saml_AuthRequest as SamlAuthRequest;


class Server extends AbstractServer
{

    /**
     * {@inheritdoc}
     */
    public function getLoginUrl()
    {
        $authRequest = new SamlAuthRequest(Util::createOneLoginSamlSettings($this));
        return $authRequest->getRedirectUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function buildValidationRequest($credentials)
    {
        // Validation request does nothing because the SAML response has all
        // the information needed to validate the credentials.
        return new Request();
    }

}
