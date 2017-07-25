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

    app/console sumocoders:multiuser:create <username> <password> <displayName> <email>

You can now go to <your domain>/en/login and login with the given user.

### Configure migrations

When you start you should initialize the migrations:

    app/console doctrine:migrations:status

## Bootstrap integration

We use ...

See [https://github.com/phiamo/MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle) for more information.

## Other

### Frontend

* [Frontend Development](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/frontend/frontend-development.md)
* [Using Gulp](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/frontend/gulp.md)
* [Configuring Webstorm/PHPStorm to use Standard JS code styling](https://blog.jetbrains.com/webstorm/2017/01/webstorm-2017-1-eap-171-2272/)

### Development

* [Creating new bundles](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/creating-new-bundles.md)
* [Fixtures](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/fixtures.md)
* [Database Changes/Migrations](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/migrations.md)
* [Adding items into the menu/navigation](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/menu.md)
* [Adding a language switch](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/language-switch.md)
* [Using Datepickers](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/using-date-pickers.md)
* [Using the breadcrumb](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/development/breadcrumb.md)

### Putting your project online

* [Deployment](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/deployment/deployment.md)

### Issues

* [Known issues](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/issues/issues.md)


