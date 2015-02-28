<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Service;

use SumoCoders\FrameworkCoreBundle\Service\KeyValueStore;

class KeyValueStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KeyValueStore
     */
    private $keyValueStore;

    /**
     * @inherit
     */
    protected function setUp()
    {
        $this->keyValueStore = new KeyValueStore();
    }

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->keyValueStore = null;
    }

    /**
     * Test KeyValueStore->get() and KeyValueStore->set()
     */
    public function testGetAndSet()
    {
        $this->keyValueStore->add('foo', 'bar');
        $this->assertEquals('bar', $this->keyValueStore->get('foo'));
    }
}
