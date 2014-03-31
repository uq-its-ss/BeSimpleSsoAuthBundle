<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Security\Core\Authentication\Token\SamlToken;
use BeSimple\SsoAuthBundle\Sso\ProtocolInterface;
use OneLogin_Saml_AuthRequest as SamlAuthRequest;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Message\Response as BuzzResponse;

class Manager
{

    /** @var \OneLogin_Saml_Settings */
    private $settings;

    public function __construct(Config $config)
    {
        $this->settings = Util::createOneLoginSamlSettings($config);
    }

    public function getLoginUrl()
    {
        $authRequest = new SamlAuthRequest($this->settings);
        return $authRequest->getRedirectUrl();
    }

    public function isValidationRequest(Request $request)
    {
        return $request->request->has('SAMLResponse');
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
        return new SamlToken($this, $request->request->get('SAMLResponse'));
    }

    public function validateToken(SamlToken $token)
    {
        $validation = new Validation(new BuzzResponse(), $token->getSamlResponse());
        $validation->setSamlSettings($this->settings);
        return $validation;
    }

}
