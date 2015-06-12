<?php

namespace SumoCoders\FrameworkCoreBundle\Tests\Twig;

use SumoCoders\FrameworkCoreBundle\Twig\FrameworkExtension;

class FrameworkExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FrameworkExtension
     */
    protected $frameworkExtension;

    /**
     * @inherit
     */
    protected function setUp()
    {
        $this->frameworkExtension = new FrameworkExtension(
            $this->getContainer()
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContainer()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $container->method('getParameter')
            ->will(
                $this->returnValue(
                    array(
                        'existing' => null,
                    )
                )
            );

        return $container;
    }

    /**
     * @inherit
     */
    protected function tearDown()
    {
        $this->frameworkExtension = null;
    }

    /**
     * Test FrameworkExtension->getFunctions()
     */
    public function testGetFunctions()
    {
        $var = $this->frameworkExtension->getFunctions();
        $this->assertInternalType('array', $var);

        $containsBundleExists = false;
        foreach ($var as $function) {
            /** @var \Twig_SimpleFunction $function */
            if ($function->getName() == 'bundleExists') {
                $containsBundleExists = true;
            }
        }
        $this->assertTrue($containsBundleExists, 'bundleExists-function not found');
    }

    public function testBundleExists()
    {
        $this->assertTrue($this->frameworkExtension->bundleExists('existing'), 'existing bundle not found');
        $this->assertFalse($this->frameworkExtension->bundleExists('non-existing'), 'non-existing bundle found');
    }

    /**
     * Test FrameworkExtension->getName()
     */
    public function testGetName()
    {
        $this->assertEquals('framework_extension', $this->frameworkExtension->getName());
    }

    public function testConvertToTranslationTrim()
    {
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation(' foo'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo '));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation(' foo '));
    }

    public function testConvertToTranslationToLowerCase()
    {
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('FOO'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('FoO'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('fOo'));
    }

    public function testConvertToTranslationReplaceFakeSpaces()
    {
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('f_o_o'));
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('f-o-o'));
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('f o o'));
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('f_o_o_'));
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('_f_o_o'));
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('_f_o_o_'));
    }

    public function testTranslationWithNumbersAtTheEnd()
    {
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo1'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo123'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo 123'));
    }

    public function testTranslationWithSumoCodersInTheString()
    {
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('SumoCoders.foo'));
        $this->assertEquals('foo.sumocoders', $this->frameworkExtension->convertToTranslation('foo.sumocoders'));
        $this->assertEquals('foo.bar', $this->frameworkExtension->convertToTranslation('SumoCoders.foo.bar'));
    }

    public function testTranslationWithBundleInTheString()
    {
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('fooBundle'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo.frameworkBundle'));
        $this->assertEquals('foo.bar', $this->frameworkExtension->convertToTranslation('FooBundle.bar'));
        $this->assertEquals('foobar', $this->frameworkExtension->convertToTranslation('Foo.barBundle'));
    }

    public function testTranslationWithDotsInTheString()
    {
        $this->assertEquals('foo.bar', $this->frameworkExtension->convertToTranslation('foo.......bar'));
    }

    public function testTranslationsWithNumbersAsSingleItems()
    {
        $this->assertEquals('f.o.o', $this->frameworkExtension->convertToTranslation('f_1_o_o'));
    }

    public function testIfFrameworkIsRemoved()
    {
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('framework_foo'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('framework_foo_framework'));
        $this->assertEquals('foo', $this->frameworkExtension->convertToTranslation('foo_framework'));
    }

    public function testIfBundleIsCleaned()
    {
        $this->assertEquals(
            'core.foo',
            $this->frameworkExtension->convertToTranslation('SumoCoders_FrameworkCoreBundle_foo')
        );
        $this->assertEquals(
            'namespacenameofthe.foo',
            $this->frameworkExtension->convertToTranslation('Namespace_NameOfTheBundle_foo')
        );
    }
}
