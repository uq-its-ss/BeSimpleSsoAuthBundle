<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Exception\ConfigNotFoundException;
use BeSimple\SsoAuthBundle\Exception\ProtocolNotFoundException;
use BeSimple\SsoAuthBundle\Exception\SamlSettingsNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Factory
{

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    /** @var array */
    private $configs;

    /** @var array */
    private $managers;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->configs = array();
        $this->managers = array();
    }

    public function addConfig($id, $service)
    {
        $this->configs[$id] = $service;
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
     * @throws ConfigNotFoundException
     */
    private function createManager($id, $checkUrl)
    {
        $parameter = sprintf('be_simple.sso_auth.manager.%s', $id);

        if (!$this->container->hasParameter($parameter)) {
            throw new ConfigNotFoundException($id);
        }

        $config = $this->container->getParameter($parameter);
        $config['server']['check_url'] = $checkUrl;

        return new Manager($this->getConfig($config['config']));
    }

    /**
     * @param array $config
     *
     * @return Settings
     *
     * @throws ConfigNotFoundException
     */
    private function getConfig(array $config)
    {
        $id = $config['id'];

        if (!array_key_exists($id, $this->configs)) {
            throw new ConfigNotFoundException($id);
        }

        return $this->container->get($this->configs[$id])->setConfig($config);
    }
}
