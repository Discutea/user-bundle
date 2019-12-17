<?php

namespace Discutea\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class DiscuteaUserExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('discutea_user.user_class', $config['user_class']);
        $container->setParameter('discutea_user.resetting.retry_ttl', $config['retry_ttl']);

        $loader->load('services.xml');
    }
}
