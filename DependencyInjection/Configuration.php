<?php

namespace BeSimple\SsoAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug)
    {
        $this->debug = (bool) $debug;
    }

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('be_simple_sso_auth');

        $serverDefinition = $treeBuilder
            ->getRootNode()
            ->fixXmlConfig('provider')
            ->useAttributeAsKey('id')
            ->prototype('array');

        $this->setComponentDefinition($serverDefinition, 'protocol');
        $this->setComponentDefinition($serverDefinition, 'server');
        $this->setComponentDefinition($serverDefinition, 'config');

        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $serverDefinition
     * @param string                                                           $name
     *
     * todo: validate component configuration
     */
    private function setComponentDefinition(ArrayNodeDefinition $serverDefinition, $name)
    {
        $serverDefinition
            ->children()
                ->arrayNode($name)
                    ->useAttributeAsKey('id')
                    ->beforeNormalization()
                        ->ifString()->then(function ($value) {
                            return array('id' => $value);
                        })
                    ->end()
                    ->prototype('scalar');
    }
}
