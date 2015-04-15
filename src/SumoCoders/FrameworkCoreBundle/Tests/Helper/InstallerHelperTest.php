<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Composer;

use SumoCoders\FrameworkCoreBundle\Helper\InstallerHelper;

class InstallerHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InstallerHelper
     */
    protected $installerHelper;

    public function setUp()
    {
        $this->installerHelper = new InstallerHelper();
    }

    public function testGetInstance()
    {
        $this->assertInstanceOf(
            '\\SumoCoders\\FrameworkCoreBundle\\Helper\\InstallerHelper',
            InstallerHelper::getInstance()
        );
    }

    public function testDecoratedMessage()
    {
        $this->assertEquals(
            '<error>I will be wrapped in an errortag.</error>',
            $this->installerHelper->getDecoratedMessage(
                'I will be wrapped in an errortag.',
                'error',
                true
            )
        );
    }

    public function testNotDecoratedMessage()
    {
        $this->assertEquals(
            'I wont be wrapped in an errortag.',
            $this->installerHelper->getDecoratedMessage(
                'I wont be wrapped in an errortag.',
                'error',
                false
            )
        );
    }

    public function testLocalProjectInformation()
    {
        $information = $this->installerHelper->extractInformationFromPath(
            '/Users/tijs/Sites/foo/bar'
        );

        $this->assertTrue($information['is_local']);
    }

    public function testNotLocalProjectInformation()
    {
        $information = $this->installerHelper->extractInformationFromPath(
            '/home/sumocoders/apps/foo/bar/releases/20150415160001'
        );

        $this->assertFalse($information['is_local']);
    }

    public function testCorrectProjectInformation()
    {
        $information = $this->installerHelper->extractInformationFromPath(
            '/Users/tijs/Sites/foo/bar'
        );

        $this->assertEquals('foo', $information['client']);
        $this->assertEquals('bar', $information['project']);
    }

    public function testNotCorrectProjectInformation()
    {
        $information = $this->installerHelper->extractInformationFromPath(
            '/home/sumocoders/apps/foo/bar/releases/20150415160001'
        );

        $this->assertNull($information['client']);
        $this->assertNull($information['project']);
    }
}
