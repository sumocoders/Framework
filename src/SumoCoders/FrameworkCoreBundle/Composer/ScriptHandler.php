<?php

namespace SumoCoders\FrameworkCoreBundle\Composer;

use Composer\Script\Event;
use Composer\IO\IOInterface;
use SumoCoders\FrameworkCoreBundle\Installer\Installer;

class ScriptHandler
{
    /**
     * Create the initial configuration for our project
     *
     * @param Event $event
     */
    public static function createInitialConfig(Event $event)
    {
        $installer = new Installer();
        $io = $event->getIO();

        // check if parameters.yml exists
        $rootDir = realpath(__DIR__ . '/../../../../');
        if (file_exists($rootDir . '/app/config/parameters.yml')) {
            $io->write(
                $installer->getDecoratedMessage(
                    'Skipping creating the initial config as parameters.yml already exists',
                    'info',
                    $io->isDecorated()
                )
            );
        } else {
            $information = $installer->extractInformationFromPath($rootDir);

            // ask all the information we need
            $config = array();
            $config['client'] = $installer->ask($io, 'client name', $information['client']);
            $config['project'] = $installer->ask($io, 'project name', $information['project']);

            $config['database_name'] = substr($config['client'], 0, 8) . '_' . substr($config['project'], 0, 7);
            $config['database_user'] = $config['database_name'];

            if ($information['is_local']) {
                $config['database_host'] = '10.11.12.13';    // this is the ip-address of our Vagrantbox
                $config['database_user'] = 'root';
                $config['database_password'] = 'root';
            }

            $config['secret'] = md5(uniqid());

            // create the database if requested
            if ($installer->askConfirmation($io, 'Should I create the database?')) {
                passthru('mysqladmin create ' . $config['database_name']);
            }

            // alter the Capfile if requested
            $capfilePath = $rootDir . '/Capfile';
            if (file_exists($capfilePath)) {
                if ($installer->askConfirmation($io, 'Should I alter the Capfile?')) {
                    $installer->updateCapfile($capfilePath, $config);
                }
            }

            // alter the dist file if requested
            $parameterYmlDistPath = $rootDir . '/app/config/parameters.yml.dist';
            if (file_exists($parameterYmlDistPath)) {
                if ($installer->askConfirmation($io, 'Should I alter parameters.yml.dist?')) {
                    $installer->updateYmlFile($parameterYmlDistPath, $config);
                }
            }
        }
    }

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
