<?php

namespace Madeyski\EpuapBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('madeyski_epuap');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('settings')
                    ->children()
                        ->scalarNode('app_id')->end()
                        ->scalarNode('pub_key_path')->end()
                        ->scalarNode('private_key_path')->end()
                        ->arrayNode('url')
                            ->children()
                                ->scalarNode('post_login_redirect')->end()
                                ->scalarNode('single_sign_on')->defaultValue('https://pz.gov.pl/dt/SingleSignOnService')->end()
                                ->scalarNode('artifact_resolve')->defaultValue('https://pz.gov.pl/dt-services/idpArtifactResolutionService')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
