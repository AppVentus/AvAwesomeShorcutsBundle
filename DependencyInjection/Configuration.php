<?php

namespace AppVentus\Awesome\ShortcutsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('av_awesome_shortcuts');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->arrayNode('contact_form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('from')->defaultValue('you@yourcompany.com')->cannotBeEmpty()->end()
                        ->scalarNode('to')->defaultValue('you@yourcompany.com')->cannotBeEmpty()->end()
                        ->scalarNode('template')->defaultValue('AvAwesomeShortcutsBundle:Contact:form.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('mail_template')->defaultValue('AvAwesomeShortcutsBundle:Contact:email.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('subject')->defaultValue('new message')->cannotBeEmpty()->end()
                        ->scalarNode('modal_title')->defaultValue('Contact us')->cannotBeEmpty()->end()
                        ->scalarNode('modal_body_content')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
