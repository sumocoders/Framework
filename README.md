# Sumocoders Framework

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
* [Adding items into the menu/navigation](./src/SumoCoders/FrameworkCoreBundle/Resources/doc/menu.md)
