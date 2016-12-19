<?php

namespace A5sys\MonitorBundle\DependencyInjection;

use A5sys\MonitorBundle\Converter\DataTypeConverter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 *
 */
class MonitorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $types = $config['types'];

        if ($config['enable'] === false) {
            $keys  = array_keys($types);
            foreach ($keys as $key) {
                $types[$key] = false;
            }
        }

        $container->setParameter('monitor.configuration.enable', $config['enable']);
        $container->setParameter('monitor.configuration.types', $types);
        $container->setParameter('monitor.configuration.slow_threshold.warning', $config['slow_threshold']['warning']);
        $container->setParameter('monitor.configuration.slow_threshold.error', $config['slow_threshold']['error']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
