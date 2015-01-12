<?php

namespace BeSimple\SsoAuthBundle\Security\Http\EntryPoint;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use BeSimple\SsoAuthBundle\Sso\Saml\Factory as SamlFactory;
use Symfony\Component\HttpKernel\HttpKernelInterface;


class SamlSsoAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{

    /** @var HttpKernel */
    private $httpKernel;

    /** @var SamlFactory */
    private $factory;

    /** @var array */
    private $config;

    /**
     * @param HttpKernel $httpKernel
     * @param SamlFactory $factory
     * @param array $config
     */
    public function __construct(HttpKernelInterface $httpKernel, SamlFactory $factory, array $config)
    {
        $this->httpKernel = $httpKernel;
        $this->factory    = $factory;
        $this->config     = $config;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $action  = $this->config['login_action'];
        $manager = $this->factory->getManager($this->config['manager'], $request->getUriForPath($this->config['check_path']));
        if ($action) {
            $subRequest = $request->duplicate(null, null, array(
                '_controller' => $action,
                'manager'   => $manager,
                'request'   => $request,
                'exception' => $authException,
            ));
            return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }
        return new RedirectResponse($manager->getLoginUrl());
    }

}
