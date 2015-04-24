# Sumocoders Framework

[![Build Status](https://travis-ci.org/sumocoders/Framework.svg?branch=master)](https://travis-ci.org/sumocoders/Framework) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sumocoders/Framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sumocoders/Framework/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/sumocoders/Framework/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sumocoders/Framework/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/a87f9056-eb3d-4383-915f-823744b39659/mini.png)](https://insight.sensiolabs.com/projects/a87f9056-eb3d-4383-915f-823744b39659)

## Installation

This is the SumoCoders Symfony Framework. You can install it using

    composer create-project -s dev sumocoders/framework .
    

## Configuration

### Initial database

Before you can do anything your database should be initialized.

    app/console doctrine:migrations:migrate

### Initial user

Add a user with

    app/console fos:user:create <username> <email> <password>

You can now go to <your domain>/en/users/login and login with the given user.

### Configure migrations

When you start you should initialize the migrations:

    app/console doctrine:migrations:status

## Bootstrap integration

We use ...

See [https://github.com/phiamo/MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle) for more information.

## Other

* [Frontend Development](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/frontend-development.md)
* [Using Grunt](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/grunt.md)
* [Deployment](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/deployment.md)
* [Fixtures](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/fixtures.md)
* [Database Changes/Migrations](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/migrations.md)
* [Adding items into the menu/navigation](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/menu.md)
* [Adding a language switch](./src/SumCoders/FrameworkCoreBundle/Resources/doc/language-switch.md)
* [Known issues](./src/SumCoders/FrameworkCoreBundle/Resources/doc/issues.md)
