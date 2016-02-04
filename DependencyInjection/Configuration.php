<?php

namespace Sidus\EAVDataGridBundle\DependencyInjection;

use Sidus\DataGridBundle\DependencyInjection\Configuration as BaseConfiguration;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends BaseConfiguration
{
    /**
     * @param NodeBuilder $dataGridDefinition
     */
    protected function appendDataGridDefinition(NodeBuilder $dataGridDefinition)
    {
        parent::appendDataGridDefinition($dataGridDefinition);
        $dataGridDefinition
            ->scalarNode('family')->isRequired()->end();
    }
}
