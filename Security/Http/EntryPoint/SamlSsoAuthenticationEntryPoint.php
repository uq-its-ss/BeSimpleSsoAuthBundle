<?php

namespace BeSimple\SsoAuthBundle\Security\Http\EntryPoint;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use BeSimple\SsoAuthBundle\Sso\Saml\Factory as SamlFactory;


class SamlSsoAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{

    /** @var SamlFactory */
    private $factory;

    /** @var array */
    private $config;

    public function __construct(SamlFactory $factory, array $config)
    {
        $this->factory = $factory;
        $this->config = $config;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $manager = $this->factory->getManager($this->config['manager'], $request->getUriForPath($this->config['check_path']));
        return new RedirectResponse($manager->getLoginUrl());
    }

}
