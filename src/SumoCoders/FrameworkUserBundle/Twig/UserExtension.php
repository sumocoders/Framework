<?php

namespace SumoCoders\FrameworkUserBundle\Twig;

use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;
use SumoCoders\FrameworkUserBundle\Entity\Admin;
use SumoCoders\FrameworkUserBundle\Entity\User;
use Twig_Extension;
use Twig_SimpleTest;

final class UserExtension extends Twig_Extension
{
    public function getTests(): array
    {
        return [
            new Twig_SimpleTest(
                'admin',
                function (BaseUser $user) {
                    return $user instanceof Admin;
                }
            ),
            new Twig_SimpleTest(
                'user',
                function (BaseUser $user) {
                    return $user instanceof User;
                }
            ),
        ];
    }

    public function getName(): string
    {
        return 'user_extension';
    }
}
