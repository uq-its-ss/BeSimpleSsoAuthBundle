<?php

namespace BeSimple\SsoAuthBundle\Security\Http\Firewall;

use BeSimple\SsoAuthBundle\Sso\Saml\Factory as SamlFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;


class SamlSsoAuthenticationListener extends AbstractAuthenticationListener
{

    private $factory;

    public function setFactory(SamlFactory $factory)
    {
        $this->factory = $factory;
    }

    protected function attemptAuthentication(Request $request)
    {
        $manager = $this->factory->getManager($this->options['manager'], $request->getUriForPath($this->options['check_path']));

        if (!$manager->isValidationRequest($request)) {
            return null;
        }

        return $this->authenticationManager->authenticate($manager->createToken($request));
    }

}
