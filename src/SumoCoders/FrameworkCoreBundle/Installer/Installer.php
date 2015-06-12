<?php

namespace SumoCoders\FrameworkCoreBundle\Installer;

use Composer\IO\IOInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Installer
{
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
        if ($isDecorated && $type !== null) {
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
        if ($default !== null) {
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

    /**
     * Update the Capfile with information provided in the config
     *
     * @param string $path
     * @param array  $config
     */
    public function updateCapfile($path, array $config = null)
    {
        $content = file_get_contents($path);

        if ($config !== null) {
            foreach ($config as $variableName => $value) {
                $content = $this->replaceSetRubyVar($variableName, $value, $content);
            }
        }

        file_put_contents($path, $content);
    }

    /**
     * Replace a variable set in Ruby with the actual value
     *
     * @param string $variableName
     * @param string $value
     * @param string $content
     * @return string
     */
    protected function replaceSetRubyVar($variableName, $value, $content)
    {
        return preg_replace(
            '/(set.*:' . $variableName . ',.*)\'.*\'/iU',
            '$1\'' . $value . '\'',
            $content
        );
    }

    /**
     * Update a YAML-file with information provided in the config
     *
     * @param string $path
     * @param array  $config
     */
    public function updateYmlFile($path, array $config = null)
    {
        $yamlParser = new Parser();
        $yamlDumper = new Dumper();

        try {
            $content = $yamlParser->parse(file_get_contents($path));

            $newContent = array(
                'parameters' => $this->arrayReplaceExisting($content['parameters'], $config)
            );

            $yamlString = $yamlDumper->dump($newContent, 2);
            file_put_contents($path, $yamlString);
        } catch (ParseException $e) {
            // ignore errors
        }
    }

    /**
     * Replaces elements from passed arrays into the first array recursively but only when the key exists.
     *
     * @param array $content
     * @param array $config
     * @return array mixed
     */
    public function arrayReplaceExisting(array $content, array $config = null)
    {
        if (null === $config) {
            return $content;
        }

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                // if it has only numeric keys we can handle it as a value
                if (array_keys($value) == range(0, count($value) - 1)) {
                    if (array_key_exists($key, $content)) {
                        $content[$key] = $value;
                    }
                } else {
                    $content[$key] = $this->arrayReplaceExisting($content[$key], $value);
                }
            } else {
                if (array_key_exists($key, $content)) {
                    $content[$key] = $value;
                }
            }
        }

        return $content;
    }
}
