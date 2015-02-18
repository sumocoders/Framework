<?php

namespace SumoCoders\FrameworkCoreBundle\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    /**
     * Install NPM
     *
     * @param Event $event
     */
    public static function installNPM(Event $event)
    {
        static::runCommandOnlyInDevMode('npm install', $event);
    }

    /**
     * Install Bower dependencies
     *
     * @param Event $event
     */
    public static function installBower(Event $event)
    {
        static::runCommandOnlyInDevMode('bower install', $event);
    }

    /**
     * Run grunt build
     *
     * @param Event $event
     */
    public static function gruntBuild(Event $event)
    {
        static::runCommandOnlyInDevMode('grunt build', $event);
    }

    /**
     * Running the given command only when we are in dev-mode
     * The output will be send directly to the output buffer
     *
     * @param string $command
     * @param Event  $event
     */
    public static function runCommandOnlyInDevMode($command, Event $event)
    {
        $io = $event->getIO();

        // make our command look nice
        if ($io->isDecorated()) {
            $formattedCommand = '<comment>' . $command . '</comment>';
        } else {
            $formattedCommand = $command;
        }

        // in production mode?
        if (!$event->isDevMode()) {
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
