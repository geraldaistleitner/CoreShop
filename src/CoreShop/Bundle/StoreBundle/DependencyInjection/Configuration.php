<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
*/

namespace CoreShop\Bundle\StoreBundle\DependencyInjection;

use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Bundle\StoreBundle\Controller\StoreController;
use CoreShop\Bundle\StoreBundle\Form\Type\StoreType;
use CoreShop\Component\Resource\Factory\Factory;
use CoreShop\Component\Store\Model\Store;
use CoreShop\Component\Store\Model\StoreInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('coreshop_store');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue(CoreShopResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;
        $this->addModelsSection($rootNode);
        $this->addPimcoreResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addModelsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('store')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->scalarNode('permission')->defaultValue('store')->cannotBeOverwritten()->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Store::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(StoreInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('admin_controller')->defaultValue(StoreController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(StoreType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addPimcoreResourcesSection(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('pimcore_admin')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('js')
                        ->addDefaultsIfNotSet()
                        ->ignoreExtraKeys(false)
                        ->children()
                            ->scalarNode('resource')->defaultValue('/bundles/coreshopstore/pimcore/js/resource.js')->end()
                            ->scalarNode('resource_store')->defaultValue('/bundles/coreshopstore/pimcore/js/resource/store.js')->end()
                            ->scalarNode('store_item')->defaultValue('/bundles/coreshopstore/pimcore/js/item.js')->end()
                            ->scalarNode('store_panel')->defaultValue('/bundles/coreshopstore/pimcore/js/panel.js')->end()
                            ->scalarNode('core_extension_data_store')->defaultValue('/bundles/coreshopstore/pimcore/js/coreExtension/data/coreShopStore.js')->end()
                            ->scalarNode('core_extension_tag_store')->defaultValue('/bundles/coreshopstore/pimcore/js/coreExtension/tags/coreShopStore.js')->end()
                            ->scalarNode('core_extension_data_store_multiselect')->defaultValue('/bundles/coreshopstore/pimcore/js/coreExtension/data/coreShopStoreMultiselect.js')->end()
                            ->scalarNode('core_extension_tag_store_multiselect')->defaultValue('/bundles/coreshopstore/pimcore/js/coreExtension/tags/coreShopStoreMultiselect.js')->end()
                        ->end()
                    ->end()
                    ->arrayNode('css')
                        ->addDefaultsIfNotSet()
                        ->ignoreExtraKeys(false)
                        ->children()
                            ->scalarNode('store_item')->defaultValue('/bundles/coreshopstore/pimcore/css/store.css')->end()
                        ->end()
                    ->end()
                    ->arrayNode('editmode_js')
                        ->addDefaultsIfNotSet()
                        ->ignoreExtraKeys(false)
                        ->children()
                            ->scalarNode('core_extension_document_tag_store')->defaultValue('/bundles/coreshopstore/pimcore/js/coreExtension/document/coreShopStore.js')->end()
                        ->end()
                    ->end()
                    ->scalarNode('permissions')
                        ->cannotBeOverwritten()
                        ->defaultValue(['store'])
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
