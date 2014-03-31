<?php

namespace BeSimple\SsoAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\DefinitionDecorator;


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
