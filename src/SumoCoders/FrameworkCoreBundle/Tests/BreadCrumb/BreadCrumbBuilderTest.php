<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\BreadCrumb;

use Knp\Menu\MenuItem;
use SumoCoders\FrameworkCoreBundle\BreadCrumb\BreadCrumbBuilder;

class BreadCrumbBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BreadCrumbBuilder
     */
    protected $breadCrumbBuilder;

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->breadCrumbBuilder = null;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEventDispatcher()
    {
        return $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getFactory($item)
    {
        /** @var \PHPUnit_Framework_MockObject_MockBuilder $factory */
        $factory = $this->getMock('\Knp\Menu\FactoryInterface');
        $factory->method('createItem')
            ->will(
                $this->returnValue(
                    $item
                )
            );

        return $factory;
    }

    protected function createSimpleBreadCrumb()
    {
        $item = new MenuItem(
            'root',
            $this->getMock('\Knp\Menu\FactoryInterface')
        );
        $factory = $this->getFactory($item);

        $this->breadCrumbBuilder = new BreadCrumbBuilder(
            $factory,
            $this->getEventDispatcher()
        );
    }

    public function testCreateBreadCrumbWithEmptyRequestAndEmptyMenu()
    {
        $this->createSimpleBreadCrumb();
        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $breadCrumb = $this->breadCrumbBuilder->createBreadCrumb($request);

        $this->assertTrue($breadCrumb->hasChildren());
        $this->assertEquals(1, count($breadCrumb->getChildren()));
    }

    public function testIfLastItemDoesNotHaveAnUri()
    {
        $this->createSimpleBreadCrumb();
        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $breadCrumb = $this->breadCrumbBuilder->createBreadCrumb($request);

        $lastChild = $breadCrumb->getLastChild();
        $this->assertNull($lastChild->getUri());
    }

    public function testIfBreadCrumbIsEmptyWhenDontExtraFromTheRequestIsEnabled()
    {
        $this->createSimpleBreadCrumb();
        $this->breadCrumbBuilder->setDontExtractFromTheRequest();

        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $breadCrumb = $this->breadCrumbBuilder->createBreadCrumb($request);

        $this->assertFalse($breadCrumb->hasChildren());
    }

    public function testIfSimpleItemIsAdded()
    {
        $this->createSimpleBreadCrumb();
        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');

        $this->breadCrumbBuilder->setDontExtractFromTheRequest();
        $this->breadCrumbBuilder->addSimpleItem('first', 'http://www.example.org');
        $this->breadCrumbBuilder->addSimpleItem('last', 'http://www.example.org');

        $breadCrumb = $this->breadCrumbBuilder->createBreadCrumb($request);

        $this->assertEquals(2, count($breadCrumb->getChildren()));

        $this->assertEquals('first', $breadCrumb->getChild('first')->getLabel());
        $this->assertEquals('http://www.example.org', $breadCrumb->getChild('first')->getUri());
        $this->assertEquals('last', $breadCrumb->getLastChild()->getLabel());
        $this->assertNull($breadCrumb->getLastChild()->getUri());
    }

    public function testIfBreadCrumbHasOnlyHomeWhenItemsIsSetWithEmptyArray()
    {
        $this->createSimpleBreadCrumb();

        $this->breadCrumbBuilder->addSimpleItem('first', 'http://www.example.org');
        $this->breadCrumbBuilder->setItems(array());

        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $breadCrumb = $this->breadCrumbBuilder->createBreadCrumb($request);

        $this->assertEquals(1, count($breadCrumb->getChildren()));
    }

    public function testIfItemIsAdded()
    {
        $this->createSimpleBreadCrumb();
        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');

        $first = new MenuItem('first', $this->getFactory(null));
        $first->setUri('http://www.example.org');
        $this->breadCrumbBuilder->addItem($first);

        $last = new MenuItem('last', $this->getFactory(null));
        $this->breadCrumbBuilder->addItem($last);

        $this->breadCrumbBuilder->setDontExtractFromTheRequest();
        $breadCrumb = $this->breadCrumbBuilder->createBreadCrumb($request);

        $this->assertEquals(2, count($breadCrumb->getChildren()));

        $this->assertEquals('first', $breadCrumb->getChild('first')->getLabel());
        $this->assertEquals('http://www.example.org', $breadCrumb->getChild('first')->getUri());
        $this->assertEquals('last', $breadCrumb->getLastChild()->getLabel());
        $this->assertNull($breadCrumb->getLastChild()->getUri());
    }
}
