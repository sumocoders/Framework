<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Service;

use SumoCoders\FrameworkCoreBundle\Service\JsData;

class JsDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsData
     */
    private $jsData;

    /**
     * @inherit
     */
    protected function setUp()
    {
        $this->jsData = new JsData();
    }

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->jsData = null;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestStack()
    {
        $currentRequest = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $currentRequest->method('getLocale')
            ->will(
                $this->returnValue('nl')
            );

        $requestStack = $this->getMock('\Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->method('getCurrentRequest')
            ->will(
                $this->returnValue(
                    $currentRequest
                )
            );

        return $requestStack;
    }

    /**
     * Test jsData->get()
     */
    public function testGet()
    {
        $this->jsData->setRequestStack($this->getRequestStack());
        $this->assertEquals('nl', $this->jsData->get('request[locale]', null, true));
    }

    /**
     * Test jsData->parse()
     */
    public function testToString()
    {
        $data = array();
        $var = (string) $this->jsData;
        $this->assertEquals(json_encode($data), $var);

        $request = new \stdClass();
        $request->locale = 'nl';
        $data = new \stdClass();
        $data->request = $request;
        $this->jsData->setRequestStack($this->getRequestStack());
        $var = (string) $this->jsData;
        $this->assertEquals(json_encode($data), $var);
    }
}
