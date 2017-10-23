<?php

namespace SumoCoders\FrameworkUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;

/**
 * @ORM\Entity(repositoryClass="SumoCoders\FrameworkUserBundle\Repository\AdminRepository")
 * @ORM\Table()
 */
final class Admin extends User
{
    public function getRoles(): array
    {
        return ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'];
    }

    public function canSwitchTo(BaseUser $user): bool
    {
        return !($user instanceof self) && !$user->isBlocked();
    }
}
