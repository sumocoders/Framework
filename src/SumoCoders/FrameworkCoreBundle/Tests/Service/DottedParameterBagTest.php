<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Service;

use SumoCoders\FrameworkCoreBundle\Service\DottedParameterBag;

class DottedParameterBagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DottedParameterBag
     */
    private $dottedParameterBag;

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->dottedParameterBag = null;
    }

    public function testGetSingleItem()
    {
        $this->dottedParameterBag = new DottedParameterBag(
            array('foo' => 'bar')
        );
        $this->assertEquals('bar', $this->dottedParameterBag->get('foo'));
    }

    public function testGetDoubleItem()
    {
        $this->dottedParameterBag = new DottedParameterBag(
            array(
                'name' => array(
                    'first' => 'John',
                    'last' => 'Doe',
                ),
            )
        );
        $this->assertEquals('John', $this->dottedParameterBag->get('name.first'));
        $this->assertEquals('Doe', $this->dottedParameterBag->get('name.last'));
    }

    public function testGetDeepItem()
    {
        $this->dottedParameterBag = new DottedParameterBag(
            array(
                'a' => array(
                    'very' => array(
                        'deep' => array(
                            'array' => array(
                                'x' => 13,
                                'y' => 37,
                            )
                        )
                    )
                )
            )
        );
        $this->assertEquals(13, $this->dottedParameterBag->get('a.very.deep.array.x'));
        $this->assertEquals(37, $this->dottedParameterBag->get('a.very.deep.array.y'));
    }

    public function testGetNumeric()
    {
        $this->dottedParameterBag = new DottedParameterBag(
            array(
                'errorcodes' => array(
                    404 => 'Not found'
                )
            )
        );
        $this->assertEquals('Not found', $this->dottedParameterBag->get('errorcodes.404'));
    }
}
