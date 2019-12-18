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
                ->scalarNode('user_class')->defaultValue('App\Entity\User')->cannotBeEmpty()->end()
                ->integerNode('retry_ttl')->defaultValue(7200)->end()
                ->scalarNode('from_email_address')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('password_validation')
                    ->children()
                        ->booleanNode('lowercase')->defaultFalse()->end()
                        ->booleanNode('uppercase')->defaultFalse()->end()
                        ->booleanNode('number')->defaultFalse()->end()
                        ->booleanNode('special_characters')->defaultFalse()->end()
                        ->booleanNode('min_length')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
