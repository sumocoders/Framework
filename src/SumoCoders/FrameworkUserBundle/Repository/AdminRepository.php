<?php

namespace SumoCoders\FrameworkUserBundle\Repository;

use SumoCoders\FrameworkMultiUserBundle\User\AbstractUserRepository;
use SumoCoders\FrameworkUserBundle\Entity\Admin;

final class AdminRepository extends AbstractUserRepository
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return Admin::class === $class;
    }
}
