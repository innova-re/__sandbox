<?php

namespace Unica\Bundle\InnovareBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;


/**
 * Class Builder
 *
 * @package Unica\Bundle\InnovareBundle\Menu
 *
 * @author Antonio Pierro <antonio.pierro@gmail.com>
 */
class Builder extends ContainerAware
{
    /**
     * Creates the header menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $isFooter = array_key_exists('is_footer', $options) ? $options['is_footer'] : false;

        $shopCategories = $this->container->get('sonata.classification.manager.category')->findBy(array('enabled' => true, 'parent' => null));

        $menuOptions = array_merge($options, array(
            'childrenAttributes' => array('class' => 'nav nav-pills'),
        ));

        $menu = $factory->createItem('main', $menuOptions);

        $shopMenuParams = array('route' => 'sonata_catalog_index');

        if (count($shopCategories) > 0 && !$isFooter) {
            $shopMenuParams = array_merge($shopMenuParams, array(
                'attributes' => array('class' => 'dropdown'),
                'childrenAttributes' => array('class' => 'dropdown-menu'),
                'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'data-target' => '#'),
                'label' => 'Products <b class="caret caret-menu"></b>',
                'extras' => array(
                    'safe_label' => true,
                )
            ));
        }

        if ($isFooter) {
            $shopMenuParams = array_merge($shopMenuParams, array(
                'attributes' => array('class' => 'span2'),
                "childrenAttributes" => array('class' => 'nav')
            ));
        }

        $shop = $menu->addChild('Shop', $shopMenuParams);

        $menu->addChild('News', array('route' => 'sonata_news_home'));

        foreach ($shopCategories as $category) {
            $shop->addChild($category->getName(), array(
                'route' => 'sonata_catalog_category',
                'routeParameters' => array(
                    'category_id'   => $category->getId(),
                    'category_slug' => $category->getSlug()),
                )
            );
        }

        $dropdownExtrasOptions = $isFooter ? array(
            'uri' => "#",
            'attributes' => array('class' => 'span2'),
            'childrenAttributes' => array('class' => 'nav'),
        ) : array(
            'uri' => "#",
            'attributes' => array('class' => 'dropdown'),
            'childrenAttributes' => array('class' => 'dropdown-menu'),
            'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'data-target' => '#'),
            'label' => 'Solutions <b class="caret caret-menu"></b>',
            'extras' => array(
                'safe_label' => true,
            )
        );
        $extras = $factory->createItem('Discover', $dropdownExtrasOptions);

        $extras->addChild('Bundles', array('route' => 'page_slug', 'routeParameters' => array('path' => '/bundles')));
        $extras->addChild('Api', array('route' => 'page_slug', 'routeParameters' => array('path' => '/api-landing')));
        $extras->addChild('Gallery', array('route' => 'sonata_media_gallery_index'));
        $extras->addChild('Media & SEO', array('route' => 'sonata_demo_media'));

        $menu->addChild($extras);

        $macroareeExtrasOptions = $isFooter ? array(
            'uri' => "#",
            'attributes' => array('class' => 'span2'),
            'childrenAttributes' => array('class' => 'nav'),
        ) : array(
            'uri' => "#",
            'attributes' => array('class' => 'dropdown'),
            'childrenAttributes' => array('class' => 'dropdown-menu'),
            'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'data-target' => '#'),
            'label' => 'Macroaree <b class="caret caret-menu"></b>',
            'extras' => array(
                'safe_label' => true,
            )
        );
        $extras = $factory->createItem('Macroaree', $macroareeExtrasOptions);

        // TODO change the Macroarea routes
        $extras->addChild('Macroarea 1', array('route' => 'page_slug', 'routeParameters' => array('path' => '/bundles')));
        $extras->addChild('Macroarea 2', array('route' => 'page_slug', 'routeParameters' => array('path' => '/api-landing')));
        $extras->addChild('Macroarea 3', array('route' => 'sonata_media_gallery_index'));
        $extras->addChild('Macroarea 4', array('route' => 'sonata_demo_media'));

        $menu->addChild($extras);

        $menu->addChild('Admin', array(
            'route' => 'page_slug',
            'routeParameters' => array(
                'path' => '/user'
            )
        ));

        if ($isFooter) {
            $menu->addChild('Legal notes', array(
                'route' => 'page_slug',
                'routeParameters' => array(
                    'path' => '/legal-notes',
                )
            ));
        }

        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        return $this->mainMenu($factory, array_merge($options, array('is_footer' => true)));
    }
}
