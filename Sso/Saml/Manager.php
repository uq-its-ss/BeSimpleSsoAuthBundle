<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\ProtocolInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function isValidationRequest(Request $request)
    {
        return $this->protocol->isValidationRequest($request);
    }

    /**
     * Creates a token from the request.
     *
     * @param Request $request
     *
     * @return \BeSimple\SsoAuthBundle\Security\Core\Authentication\Token\SsoToken
     */
    public function createToken(Request $request)
    {
        return new SsoToken($this, $this->protocol->extractCredentials($request));
    }

}
