<?php

namespace SumoCoders\FrameworkCoreBundle\Helper;

use Composer\IO\IOInterface;

class InstallerHelper
{
    /**
     * @var InstallerHelper
     */
    protected static $instance;

    /**
     * Grab an instance, Singleton ftw
     *
     * @return InstallerHelper
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new InstallerHelper();
        }

        return static::$instance;
    }

    /**
     * Get a decorated message
     *
     * @param string $message
     * @param mixed  $type
     * @param bool   $isDecorated
     * @return string
     */
    public function getDecoratedMessage($message, $type = null, $isDecorated = true)
    {
        if ($isDecorated && null !== $type) {
            $message = '<' . $type . '>' . $message . '</' . $type . '>';
        }

        return $message;
    }

    /**
     * @param IOInterface $io
     * @param string      $question
     * @param mixed       $default
     * @return string
     */
    public function ask(IOInterface $io, $question, $default = null)
    {
        $question = $this->getDecoratedMessage($question, 'question', $io->isDecorated()) . ' ';
        if (null !== $default) {
            $question .= '(' . $this->getDecoratedMessage($default, 'comment', $io->isDecorated()) . ')';
        }
        $question .= ': ';

        return $io->ask($question, $default);
    }

    /**
     * @param IOInterface $io
     * @param string      $question
     * @param bool        $default
     * @return bool
     */
    public function askConfirmation(IOInterface $io, $question, $default = true)
    {
        $question = $this->getDecoratedMessage($question, 'question', $io->isDecorated()) . ' ';
        $question .= '(Y/n): ';

        return $io->askConfirmation($question, $default);
    }

    /**
     * Extract the client and project from a given path
     * As everyone should use the same setup we can get the client en project from the path.
     *
     * @param string $path
     * @return array
     */
    public function extractInformationFromPath($path)
    {
        $defaultReturn = array(
            'is_local' => (substr($path, 0, 7) == '/Users/'),
            'client' => null,
            'project' => null,
        );
        $chunks = explode('/', mb_strtolower(trim($path, '/')));
        $sitesOffset = array_search('sites', $chunks);

        if ($sitesOffset) {
            if (isset($chunks[$sitesOffset + 1])) {
                $defaultReturn['client'] = $chunks[$sitesOffset + 1];
            }
            if (isset($chunks[$sitesOffset + 2])) {
                $defaultReturn['project'] = $chunks[$sitesOffset + 2];
            }
        }

        return $defaultReturn;
    }
}
