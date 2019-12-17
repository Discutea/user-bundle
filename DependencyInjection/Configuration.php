<?php

namespace Discutea\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('discutea_user');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('user_class')
                    ->defaultValue('App\Entity\User')
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('retry_ttl')
                    ->defaultValue(7200)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
