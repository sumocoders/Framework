<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Composer;

use SumoCoders\FrameworkCoreBundle\Composer\ScriptHandler;

class ScriptHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEvent()
    {
        $io = $this->getMockBuilder('\Composer\IO\ConsoleIO')
            ->disableOriginalConstructor()
            ->getMock();
        $io->method('isDecorated')
            ->willReturn(true);

        $this->assertTrue($io->isDecorated());


        $event = $this->getMockBuilder('\Composer\Script\Event')
            ->disableOriginalConstructor()
            ->getMock();
        $event->method('isDevMode')
            ->willReturn(true);
        $event->method('getIO')
            ->willReturn($io);

        return $event;
    }

    /**
     * Test ScriptHandler->runCommandOnlyInDevMode()
     */
    public function testRunCommandOnlyInDevMode()
    {
        $this->expectOutputString('foo' . "\n");
        ScriptHandler::runCommandOnlyInDevMode('echo "foo"', $this->getEvent());
    }
}
