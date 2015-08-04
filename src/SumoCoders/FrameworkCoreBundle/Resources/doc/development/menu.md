# Adding items into the menu/navigation

Because we want a modular system we are using events to build the menu. When
the menu is build an event called "framework_core.configure_menu" is triggered.

Your bundle can subscribe on this event. Follow the steps below to implement it
in your own bundle

## Create a listener

```php
<?php

namespace SumoCoders\FrameworkExampleBundle\EventListener;

use SumoCoders\FrameworkCoreBundle\Event\ConfigureMenuEvent;
use SumoCoders\FrameworkCoreBundle\EventListener\DefaultMenuListener;

class MenuListener extends DefaultMenuListener
{
    public function onConfigureMenu(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        // if you need to add a menu with subitems you can use a shortcut like this
        $childs = array(
            'this.is.the.label' => 'sumocoders_frameworkexample_route_name',
            'another.label' => $event->getFactory()->createItem(
                'another.label',
                array(
                    'route' => 'sumocoders_frameworkexample_default_hello',
                    'routeParameters' => array(
                        'name' => 'John Doe',
                    ),
                )
            ),
        );

        $menuItem = $this->createItemWithChilds(
            $event->getFactory(),
            'a.label',
            1000,
            $childs
        );

        $menu->addChild($menuItem);

        $menuItem = $event->getFactory()->createItem(
            'example.menu.overview',
            array(
                'route' => 'sumocoders_frameworkexample_route_name',
            )
        );
    }
}
```

## Listen on the event

Add the configuration

```yml
services:
  example_menu_listener:
    class: SumoCoders\FrameworkExampleBundle\EventListener\MenuListener
    arguments:
      - @security.authorization_checker
      - @security.token_storage
    tags:
      - { name: kernel.event_listener, event: framework_core.configure_menu, method: onConfigureMenu }
```

## Some guidelines

You can use normal numbers such as 1, 2, 3. But I would advice that you use
hundreds, as it will allow you to insert items in between in a later stage.
