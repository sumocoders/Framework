<?php

namespace SumoCoders\FrameworkUserBundle\Repository;

use SumoCoders\FrameworkMultiUserBundle\User\AbstractUserRepository;
use SumoCoders\FrameworkUserBundle\Entity\User;

final class UserRepository extends AbstractUserRepository
{
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
