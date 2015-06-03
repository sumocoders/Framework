<?php

namespace SumoCoders\FrameworkCoreBundle\BreadCrumb;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use SumoCoders\FrameworkCoreBundle\Event\ConfigureMenuEvent;

final class BreadCrumbBuilder
{
    /**
     * @var bool
     */
    private $dontExtractFromTheRoute = false;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $items = array();

    /**
     * @param FactoryInterface         $factory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Add items into the breadcrumb based on a given child.
     *
     * @param MenuItem $item
     * @param string   $locale
     */
    protected function addItemsBasedOnTheChild(MenuItem $item, $locale)
    {
        if (null !== $item) {
            $items = array();
            $temporaryItem = $item;

            while (null !== $temporaryItem->getParent()) {
                $breadCrumb = new MenuItem($temporaryItem->getName(), $this->factory);
                $breadCrumb->setLabel($temporaryItem->getLabel());

                if ('#' !== $temporaryItem->getUri() && null !== $temporaryItem->getUri()) {
                    $breadCrumb->setUri($temporaryItem->getUri());
                }
                $items[] = $breadCrumb;

                $temporaryItem = $temporaryItem->getParent();
            }

            $home = new MenuItem('core.menu.home', $this->factory);
            $home->setLabel('core.menu.home');
            $home->setUri('/' . $locale);
            $this->items[] = $home;

            $this->items = array_merge($this->items, array_reverse($items));
        }
    }

    /**
     * Grab the menu
     *
     * This method will use the ConfigureMenuEvent to get all the items
     *
     * @return \Knp\Menu\ItemInterface
     */
    protected function getTheMenu()
    {
        $menu = $this->factory->createItem('root');

        $this->eventDispatcher->dispatch(
            ConfigureMenuEvent::EVENT_NAME,
            new ConfigureMenuEvent(
                $this->factory,
                $menu
            )
        );

        return $menu;
    }

    /**
     * Find an item in the menu based on its URI
     *
     * @param MenuItem $menuItem
     * @param          $uri
     * @return MenuItem|null
     */
    protected function findItemBasedOnUri(MenuItem $menuItem, $uri)
    {
        if ($uri === $menuItem->getUri()) {
            return $menuItem;
        }

        if (!$menuItem->hasChildren()) {
            return null;
        }

        foreach ($menuItem->getChildren() as $child) {
            $item = $this->findItemBasedOnUri(
                $child,
                $uri
            );

            if (null !== $item) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Extract the items for the breadcrumb based on request
     *
     * @param Request $request
     */
    protected function extractItemsBasedOnTheRequest(Request $request)
    {
        if ($this->dontExtractFromTheRoute) {
            return;
        }

        $this->extractItemsBasedOnUri(
            $request->getPathInfo(),
            $request->getLocale()
        );
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function createBreadCrumb(Request $request)
    {
        $this->extractItemsBasedOnTheRequest($request);

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'breadcrumb');

        foreach ($this->items as $i => $item) {
            // the last item shouldn't have a link
            if ((count($this->items) - 1) == $i) {
                $item->setUri(null);
            }

            $menu->addChild($item);
        }

        return $menu;
    }

    /**
     * @param bool $isCustom
     * @return BreadCrumbBuilder
     */
    public function setDontExtractFromTheRequest($isCustom = true)
    {
        $this->dontExtractFromTheRoute = $isCustom;

        return $this;
    }

    /**
     * Extract the items for the breadcrumb based on a uri
     *
     * @param string $uri
     * @param string $locale
     * @return BreadCrumbBuilder
     */
    public function extractItemsBasedOnUri($uri, $locale)
    {
        $this->setDontExtractFromTheRequest(true);

        $item = $this->findItemBasedOnUri(
            $this->getTheMenu(),
            $uri
        );

        if (null !== $item) {
            $this->addItemsBasedOnTheChild($item, $locale);
        }

        return $this;
    }

    /**
     * Add an item into the breadcrumb
     *
     * @param MenuItem $item
     * @return BreadCrumbBuilder
     */
    public function addItem(MenuItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Add a item into the breadcrumb by passing the label and an optional URI.
     *
     * @param string $label
     * @param string $uri
     * @return BreadCrumbBuilder
     */
    public function addSimpleItem($label, $uri = null)
    {
        $item = new MenuItem($label, $this->factory);
        $item->setLabel($label);
        if (null !== $uri) {
            $item->setUri($uri);
        }

        $this->items[] = $item;

        return $this;
    }

    /**
     * Reset the items and add the given items all at once
     *
     * @param array $items
     * @return BreadCrumbBuilder
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
