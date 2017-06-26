<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Event;

use SumoCoders\FrameworkCoreBundle\Event\ConfigureMenuEvent;

class ConfigureMenuEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigureMenuEvent
     */
    protected $configureMenuEvent;

    /**
     * @inherit
     */
    protected function setUp()
    {
        $this->configureMenuEvent = new ConfigureMenuEvent(
            $this->getFactory(),
            $this->getItem()
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getFactory()
    {
        $factory = $this->getMockBuilder('Knp\Menu\FactoryInterface')->getMock();

        return $factory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getItem()
    {
        $item = $this->getMockBuilder('Knp\Menu\ItemInterface')->getMock();

        return $item;
    }

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->configureMenuEvent = null;
    }

    /**
     * Test FrameworkExtension->getName()
     */
    public function testName()
    {
        $this->assertEquals('framework_core.configure_menu', ConfigureMenuEvent::EVENT_NAME);
    }

    /**
     * Test the getters and setters
     */
    public function testGettersAndSetters()
    {
        $this->assertEquals($this->getFactory(), $this->configureMenuEvent->getFactory());
        $this->assertEquals($this->getItem(), $this->configureMenuEvent->getMenu());
    }
}
