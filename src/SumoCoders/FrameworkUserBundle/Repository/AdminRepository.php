<?php

namespace SumoCoders\FrameworkUserBundle\Repository;

use SumoCoders\FrameworkMultiUserBundle\User\UserRepository as FrameworkUserRepository;
use SumoCoders\FrameworkUserBundle\Entity\Admin;

final class AdminRepository extends FrameworkUserRepository
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return Admin::class === $class;
    }
}
