<?php

namespace SumoCoders\FrameworkCoreBundle\EventListener;

use SumoCoders\FrameworkCoreBundle\Event\ConfigureMenuEvent;
use SumoCoders\FrameworkCoreBundle\EventListener\DefaultMenuListener;

class MenuListener extends DefaultMenuListener
{
    public function onConfigureMenu(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        if ($this->getSecurityAuthorizationChecker()->isGranted('ROLE_ADMIN')) {
            $menuItem = $event->getFactory()->createItem(
                'navigation.translations',
                array(
                    'route' => 'jms_translation_index',
                )
            );
            $menuItem->setLinkAttributes(
                array(
                    'class' => 'menu-item',
                )
            );

            $menu->addChild($menuItem);
        }
    }
}
