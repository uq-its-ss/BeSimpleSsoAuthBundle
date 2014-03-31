<?php

namespace BeSimple\SsoAuthBundle;

use BeSimple\SsoAuthBundle\DependencyInjection\Compiler\FactoryPass;
use BeSimple\SsoAuthBundle\DependencyInjection\Security\Factory\SamlSsoFactory;
use BeSimple\SsoAuthBundle\DependencyInjection\Security\Factory\TrustedSsoFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class BeSimpleSsoAuthBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $ext = $container->getExtension('security');
        $ext->addSecurityListenerFactory(new TrustedSsoFactory());
        $ext->addSecurityListenerFactory(new SamlSsoFactory());

        $container->addCompilerPass(new FactoryPass());
    }
}
