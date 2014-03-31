<?php

namespace BeSimple\SsoAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;


class SamlSsoFactory extends AbstractSsoFactory
{

    public function __construct()
    {
        parent::__construct();
        $this->addOption('manager');
    }

    public function getKey()
    {
        return 'saml_sso';
    }

    protected function getListenerId()
    {
        return 'security.authentication.listener.saml_sso';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'security.authentication.provider.saml_sso.'.$id;

        $container
            ->setDefinition($provider, new DefinitionDecorator('security.authentication.provider.saml_sso'))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(2, $config['create_users'])
            ->replaceArgument(3, $config['created_users_roles'])
        ;

        return $provider;
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'security.authentication.saml_sso_entry_point.'.$id;

        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.saml_sso_entry_point'))
            ->addArgument($config)
        ;

        return $entryPointId;
    }

}
