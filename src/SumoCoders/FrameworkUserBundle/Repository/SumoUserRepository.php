<?php

namespace SumoCoders\FrameworkUserBundle\Repository;

use SumoCoders\FrameworkMultiUserBundle\User\UserRepository as FrameworkUserRepository;
use SumoCoders\FrameworkUserBundle\Entity\SumoUser;

final class SumoUserRepository extends FrameworkUserRepository
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return SumoUser::class === $class;
    }
}
