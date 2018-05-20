<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\ApiBundle\DependencyInjection;


use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ApiExtension extends Extension
{
    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'todo';
    }

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator([__DIR__ . '/../Resources/config/services'])
        );

        $loader->load('api_bundle.yml');
        $loader->load('core.yml');
        $loader->load('infrastructure.yml');

        $this->processConfiguration(new Configuration(), $configs);
    }
}