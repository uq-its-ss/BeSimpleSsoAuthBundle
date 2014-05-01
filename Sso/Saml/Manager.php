<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Security\Core\Authentication\Token\SamlToken;
use BeSimple\SsoAuthBundle\Sso\ProtocolInterface;
use OneLogin_Saml2_AuthnRequest as Saml2AuthnRequest;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Message\Response as BuzzResponse;

class Manager
{

    /** @var \OneLogin_Saml2_Settings */
    private $settings;

    public function __construct(Config $config)
    {
        $this->settings = Util::createOneLoginSamlSettings($config);
    }

    public function getLoginUrl()
    {
        $authnRequest = new Saml2AuthnRequest($this->settings);
        $samlRequest = $authnRequest->getRequest();
        $parameters = array('SAMLRequest' => $samlRequest);

        $idpData = $this->settings->getIdPData();
        $ssoUrl = $idpData['singleSignOnService']['url'];

        return OneLogin_Saml2_Utils::redirect($ssoUrl, $parameters, true);
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
