<?php

namespace Discutea\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const ALIAS = 'md_socom';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('discutea_user');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('user_class')->defaultValue('App\Entity\User')->cannotBeEmpty()->end()
                ->integerNode('retry_ttl')->defaultValue(7200)->end()
                ->scalarNode('from_email_address')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('password_validation')
                    ->children()
                        ->integerNode('lowercase')->defaultValue(0)->end()
                        ->integerNode('uppercase')->defaultValue(0)->end()
                        ->integerNode('number')->defaultValue(0)->end()
                        ->integerNode('special_characters')->defaultValue(0)->end()
                        ->integerNode('min_length')->defaultValue(0)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
