<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use Symfony\Component\DependencyInjection\ContainerInterface;


class Factory
{

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    /** @var array */
    private $protocols;

    /** @var array */
    private $managers;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addProtocol($id, $service)
    {
        $this->protocols[$id] = $service;
    }

    public function getManager($id, $checkUrl)
    {
        if (!array_key_exists($id, $this->managers)) {
            $this->managers[$id] = $this->createManager($id, $checkUrl);
        }

        return $this->managers[$id];
    }

    /**
     * @param string $id
     * @param string $checkUrl
     *
     * @return Manager
     *
     * @throws \BeSimple\SsoAuthBundle\Exception\ConfigNotFoundException
     */
    private function createManager($id, $checkUrl)
    {
        $parameter = sprintf('be_simple.sso_auth.manager.%s', $id);

        if (!$this->container->hasParameter($parameter)) {
            throw new ConfigNotFoundException($id);
        }

        $config = $this->container->getParameter($parameter);
        $config['server']['check_url'] = $checkUrl;

        return new Manager($this->getProtocol($config['protocol']));
    }

}
