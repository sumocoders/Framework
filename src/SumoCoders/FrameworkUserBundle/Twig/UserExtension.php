<?php

namespace SumoCoders\FrameworkUserBundle\Twig;

use SumoCoders\FrameworkMultiUserBundle\Entity\User;
use SumoCoders\FrameworkUserBundle\Entity\Admin;
use SumoCoders\FrameworkUserBundle\Entity\SumoUser;
use Twig_Extension;
use Twig_SimpleTest;

final class UserExtension extends Twig_Extension
{
    public function getTests(): array
    {
        return [
            new Twig_SimpleTest(
                'admin',
                function (User $user) {
                    return $user instanceof Admin;
                }
            ),
            new Twig_SimpleTest(
                'user',
                function (User $user) {
                    return $user instanceof SumoUser;
                }
            ),
        ];
    }

    public function getName(): string
    {
        return 'user_extension';
    }
}
