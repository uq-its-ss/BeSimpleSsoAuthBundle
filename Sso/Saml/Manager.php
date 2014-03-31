<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Security\Core\Authentication\Token\SamlToken;
use BeSimple\SsoAuthBundle\Sso\ProtocolInterface;
use OneLogin_Saml_AuthRequest as SamlAuthRequest;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Message\Response as BuzzResponse;

class Manager
{

    private $protocol;

    public function __construct(ProtocolInterface $protocol)
    {
        $this->protocol = $protocol;
    }

    public function getLoginUrl()
    {
        $authRequest = new SamlAuthRequest($this->createSamlSettings());
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
     * @return SamlToken
     */
    public function createToken(Request $request)
    {
        return new SamlToken($this, $this->protocol->extractCredentials($request));
    }

    public function validateToken(SamlToken $token)
    {
        $validation = new Validation(new BuzzResponse(), $token->getSamlResponse());
        $validation->setSamlSettings($this->createSamlSettings());
        return $validation;
    }

    private function createSamlSettings()
    {
        return Util::createOneLoginSamlSettings($this->protocol);
    }

}
