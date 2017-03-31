<?php

namespace Sidus\EAVDataGridBundle\DependencyInjection;

use Sidus\DataGridBundle\DependencyInjection\SidusDataGridExtension;
use Sidus\EAVFilterBundle\DependencyInjection\Configuration as FilterConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use UnexpectedValueException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SidusEAVDataGridExtension extends SidusDataGridExtension
{
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationParser()
    {
        return new Configuration('sidus_eav_data_grid');
    }

    /**
     * {@inheritdoc}
     */
    protected function createFilterConfigurationParser()
    {
        return new FilterConfiguration();
    }

    /**
     * Add a new Filter service based on the configuration passed inside the datagrid
     *
     * @param string           $code
     * @param array            $dataGridConfiguration
     * @param ContainerBuilder $container
     *
     * @throws BadMethodCallException
     * @throws UnexpectedValueException
     * @throws InvalidArgumentException
     */
    protected function addDataGridServiceDefinition($code, array $dataGridConfiguration, ContainerBuilder $container)
    {
        $dataGridConfiguration = $this->finalizeConfiguration($code, $dataGridConfiguration, $container);

        $definition = new Definition(
            new Parameter('sidus_eav_data_grid.model.datagrid.class'),
            [
                $code,
                $dataGridConfiguration,
                new Reference('translator'),
            ]
        );
        $definition->addTag('sidus.datagrid');
        $definition->setPublic(false);
        $container->setDefinition('sidus_eav_data_grid.datagrid.'.$code, $definition);
    }

    /**
     * Handle direct configuration of filters, uses the same logic than the FilterBundle to generate a service
     *
     * @param                  $code
     * @param array            $dataGridConfiguration
     * @param ContainerBuilder $container
     *
     * @return Reference
     * @throws BadMethodCallException
     */
    protected function addFilterConfiguration($code, array $dataGridConfiguration, ContainerBuilder $container)
    {
        $dataGridConfiguration['filter_config']['family'] = $dataGridConfiguration['family'];
        $filterConfig = $this->finalizeFilterConfiguration($code, $dataGridConfiguration['filter_config']);

        $definition = new Definition(
            new Parameter('sidus_eav_filter.configuration.class'),
            [
                $code,
                new Reference('doctrine'),
                new Reference('sidus_filter.filter.factory'),
                $filterConfig,
                new Reference('sidus_eav_model.family_configuration.handler'),
            ]
        );
        $definition->setPublic(false);

        $serviceId = 'sidus_eav_filter.datagrid.configuration.'.$code;
        $container->setDefinition($serviceId, $definition);

        return new Reference($serviceId);
    }
}
