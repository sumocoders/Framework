<?php

namespace SumoCoders\FrameworkCoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FrameworkExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get the registered functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'bundleExists',
                array($this, 'bundleExists')
            ),
            new \Twig_SimpleFunction(
                'toTranslation',
                array($this, 'convertToTranslation')
            ),
        );
    }

    /**
     * Check if a bundle exists
     *
     * @param string $bundle
     * @return bool
     */
    public function bundleExists($bundle)
    {
        $bundles = $this->container->getParameter('kernel.bundles');

        return array_key_exists($bundle, $bundles);
    }

    /**
     * Convert a given string into a string that will/can be used as a id for
     * translations
     *
     * @param string $stringToConvert
     * @return string
     */
    public function convertToTranslation($stringToConvert)
    {
        $stringToConvert = trim($stringToConvert);
        $stringToConvert = mb_strtolower($stringToConvert);
        $stringToConvert = str_replace(
            array('_', '-', ' ', 'bundle', 'framework'),
            '.',
            $stringToConvert
        );

        if (substr($stringToConvert, 0, 11) == 'sumocoders.') {
            $stringToConvert = substr($stringToConvert, 11);
        }

        // remove numbers if they appear at the end
        $stringToConvert = preg_replace('/\d+$/', '', $stringToConvert);

        $stringToConvert = preg_replace('/\.+/', '.', $stringToConvert);

        return trim($stringToConvert, '.');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'framework_extension';
    }
}
