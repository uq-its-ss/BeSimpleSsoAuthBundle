<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\ProtocolInterface;

class Manager
{

    private $protocol;

    public function __construct(ProtocolInterface $protocol)
    {
        $this->protocol = $protocol;
    }

    public function getLoginUrl()
    {
        $authRequest = new SamlAuthRequest(Util::createOneLoginSamlSettings($this->protocol));
        return $authRequest->getRedirectUrl();
    }

}
