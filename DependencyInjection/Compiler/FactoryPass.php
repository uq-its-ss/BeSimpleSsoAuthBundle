<?php

namespace BeSimple\SsoAuthBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class FactoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('be_simple.sso_auth.factory')) {
            $this->processDefaultFactory($container);
        }
        if ($container->has('be_simple.sso_auth.saml_factory')) {
            $this->processSamlFactory($container);
        }
    }

    private function processDefaultFactory($container)
    {
        $factoryBuilder = $container->getDefinition('be_simple.sso_auth.factory');

        foreach ($container->findTaggedServiceIds('be_simple.sso_auth.protocol') as $id => $attributes) {
            $factoryBuilder->addMethodCall('addProtocol', array($attributes[0]['id'], $id));
        }

        foreach ($container->findTaggedServiceIds('be_simple.sso_auth.server') as $id => $attributes) {
            $factoryBuilder->addMethodCall('addServer', array($attributes[0]['id'], $id));
        }
    }

    private function processSamlFactory($container)
    {
        $factoryBuilder = $container->getDefinition('be_simple.sso_auth.saml_factory');

        foreach ($container->findTaggedServiceIds('be_simple.sso_auth.protocol.saml') as $id => $attributes) {
            $factoryBuilder->addMethodCall('addProtocol', array($attributes[0]['id'], $id));
        }
    }

}
