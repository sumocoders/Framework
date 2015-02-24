<?php

namespace SumoCoders\FrameworkCoreBundle\Composer;

use Composer\Script\Event;
use Composer\IO\IOInterface;

class ScriptHandler
{
    /**
     * Install NPM
     *
     * @param Event $event
     */
    public static function installNPM(Event $event)
    {
        static::runCommandOnlyInDevMode('npm install', $event->getIO(), $event->isDevMode());
    }

    /**
     * Install Bower dependencies
     *
     * @param Event $event
     */
    public static function installBower(Event $event)
    {
        static::runCommandOnlyInDevMode('bower install', $event->getIO(), $event->isDevMode());
    }

    /**
     * Run grunt build
     *
     * @param Event $event
     */
    public static function gruntBuild(Event $event)
    {
        static::runCommandOnlyInDevMode('grunt build', $event->getIO(), $event->isDevMode());
    }

    /**
     * Running the given command only when we are in dev-mode
     * The output will be send directly to the output buffer
     *
     * @param string      $command
     * @param IOInterface $io
     * @param boolean     $isDevMode
     *
     */
    public static function runCommandOnlyInDevMode($command, IOInterface $io, $isDevMode)
    {
        // make our command look nice
        if ($io->isDecorated()) {
            $formattedCommand = '<comment>' . $command . '</comment>';
        } else {
            $formattedCommand = $command;
        }

        // in production mode?
        if (!$isDevMode) {
            $io->write(
                sprintf(
                    'Skipping %1$s as we are in production mode',
                    $formattedCommand
                )
            );
        } else {
            $io->write(
                sprintf(
                    'Running %1$s',
                    $formattedCommand
                )
            );
            passthru($command);
        }
    }
}
