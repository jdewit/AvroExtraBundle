<?php
namespace Avro\ExtraBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Alias;

class AvroExtraExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) {

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load($config['db_driver'].'.yml');

        if ($config['menu']) {
            $loader->load('menu.yml');
        }
        if ($config['twig']) {
            $loader->load('twig.yml');
        }
        if ($config['form']) {
            $loader->load('form.yml');
        }
        if ($config['ajax_authentication']) {
            $loader->load('ajax_authentication.yml');
        }
        if ($config['exception']) {
            $loader->load('exception.yml');
        }
        if ($config['param_converter']) {
            $loader->load('param_converter.yml');
        }
    }
}

