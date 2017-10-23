<?php

namespace SumoCoders\FrameworkUserBundle\Repository;

use SumoCoders\FrameworkMultiUserBundle\User\AbstractUserRepository;
use SumoCoders\FrameworkUserBundle\Entity\User;

final class UserRepository extends AbstractUserRepository
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
