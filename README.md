# Sumocoders Framework

[![Build Status](https://travis-ci.org/sumocoders/Framework.svg?branch=master)](https://travis-ci.org/sumocoders/Framework) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sumocoders/Framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sumocoders/Framework/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/sumocoders/Framework/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sumocoders/Framework/?branch=master)

## Installation

This is the SumoCoders Symfony Framework. You can install it using

    composer create-project -s dev sumocoders/framework .

## Configuration

### Initial user

Add a user with

    app/console fos:user:create <username> <email> <password>

You can now go to <your domain>/en/users/login and login with the given user.

## Error handling

Enable the FrameworkErrorBundle in the kernel, just add it in production mode, as this bundle
is intended to handle errors so our visitors don't freak out.

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    // ...
    if (in_array($this->getEnvironment(), array('prod'))) {
        $bundles[] = new SumoCoders\FrameworkErrorBundle\SumoCodersFrameworkErrorBundle();
    }
}
```

## Bootstrap integration

We use ...

See [https://github.com/phiamo/MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle) for more information.

## Other

* [Using Grunt](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/grunt.md)
* [Deployment](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/deployment.md)
* [Adding items into the menu/navigation](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/menu.md)
* [Known issues](./src/SumCoders/FrameworkCoreBundle/Resources/doc/issues.md)
