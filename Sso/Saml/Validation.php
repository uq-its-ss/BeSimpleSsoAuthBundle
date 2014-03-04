<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractValidation;
use BeSimple\SsoAuthBundle\Sso\ValidationInterface;
use Buzz\Message\Response;
use OneLogin_Saml_Response as SamlResponse;
use OneLogin_Saml_Settings as SamlSettings;

class Validation extends AbstractValidation implements ValidationInterface
{

    private $samlSettings;

    public function setSamlSettings(SamlSettings $settings)
    {
        $this->samlSettings = $settings;
    }

    protected function validateResponse(Response $response)
    {
        $assertion = $this->getCredentials();
        $samlResponse = new SamlResponse($this->samlSettings, $assertion);

        $success = false;

        try {
            $success = $samlResponse->isValid();
        } catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }

        if ($success) {
            $this->username = $samlResponse->getNamedId();
            $this->attributes = $samlResponse->getAttributes();
        }

        return $success;
    }

}
