<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractValidation;
use BeSimple\SsoAuthBundle\Sso\ValidationInterface;
use Buzz\Message\Response;
use OneLogin\Saml2\Response as Saml2Response;
use OneLogin\Saml2\Settings as Saml2Settings;

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

        $success = $samlResponse->isValid();
        if ($success) {
            $this->username = $samlResponse->getNameId();
            $this->attributes = $samlResponse->getAttributes();
        } else {
            $this->error = $samlResponse->getError();
        }

        return $success;
    }

}
