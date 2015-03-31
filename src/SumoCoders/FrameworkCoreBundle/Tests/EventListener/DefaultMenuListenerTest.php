<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\EventListener;

use SumoCoders\FrameworkCoreBundle\EventListener\DefaultMenuListener;

class DefaultMenuListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultMenuListener
     */
    protected $defaultMenuListener;

    /**
     * @inherit
     */
    protected function setUp()
    {
        $this->defaultMenuListener = new DefaultMenuListener(
            $this->getSecurityAuthorizationChecker(),
            $this->getSecurityTokenStorage()
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSecurityAuthorizationChecker()
    {
        $container = $this->getMock(
            '\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface'
        );

        return $container;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSecurityTokenStorage()
    {
        $securityContext = $this->getMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            array(),
            array(),
            '',
            false
        );

        return $securityContext;
    }

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->defaultMenuListener = null;
    }

    /**
     * Test the getters and setters
     */
    public function testGettersAndSetters()
    {
        $securityAuthorizationChecker = $this->getSecurityAuthorizationChecker();
        $this->assertEquals(
            $securityAuthorizationChecker,
            $this->defaultMenuListener->getSecurityAuthorizationChecker()
        );

        $securityTokenStorage = $this->getSecurityTokenStorage();
        $this->assertEquals($securityTokenStorage, $this->defaultMenuListener->getSecurityTokenStorage());
    }
}
