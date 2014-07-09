<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractValidation;
use BeSimple\SsoAuthBundle\Sso\ValidationInterface;
use Buzz\Message\Response;
use OneLogin_Saml2_Response as Saml2Response;
use OneLogin_Saml2_Settings as Saml2Settings;

class Validation extends AbstractValidation implements ValidationInterface
{

    private $samlSettings;

    public function setSamlSettings(Saml2Settings $settings)
    {
        $this->samlSettings = $settings;
    }

    protected function validateResponse(Response $unusedResponse)
    {
        $assertion = $this->getCredentials();
        $samlResponse = new Saml2Response($this->samlSettings, $assertion);

        if ($samlResponse->isValid()) {
            $this->username = $samlResponse->getNameId();
            $this->attributes = $samlResponse->getAttributes();
        } else {
            $this->error = $samlResponse->getError();
        }

        return $success;
    }

}
