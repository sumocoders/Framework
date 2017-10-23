<?php

namespace SumoCoders\FrameworkUserBundle\EventListener;

use SumoCoders\FrameworkCoreBundle\Event\ConfigureMenuEvent;
use SumoCoders\FrameworkCoreBundle\EventListener\DefaultMenuListener;

final class MenuListener extends DefaultMenuListener
{
    public function onConfigureMenu(ConfigureMenuEvent $event): void
    {
        if (!$this->getSecurityAuthorizationChecker()->isGranted('ROLE_ADMIN')) {
            return;
        }

        $menu = $event->getMenu();
        $menuItem = $event->getFactory()->createItem(
            'user.menu.index',
            [
                'route' => 'sumocoders_frameworkuser_index_index',
            ]
        );
        $menuItem->setExtra('orderNumber', 50);

        $menu->addChild($menuItem);
    }
}
