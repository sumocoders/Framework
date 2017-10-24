<?php

namespace SumoCoders\FrameworkUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;
use SumoCoders\FrameworkMultiUserBundle\Security\PasswordResetToken;

/**
 * @ORM\Entity(repositoryClass="SumoCoders\FrameworkUserBundle\Repository\AdminRepository")
 * @ORM\Table()
 */
final class Admin extends BaseUser
{
    public function __construct(
        string $plainPassword,
        string $displayName,
        string $email,
        int $id = null,
        PasswordResetToken $token = null
    ) {
        parent::__construct($email, $plainPassword, $displayName, $email, $id, $token);
    }

    public function getRoles(): array
    {
        return ['ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'];
    }

    public function canSwitchTo(BaseUser $user): bool
    {
        return !($user instanceof self) && !$user->isBlocked();
    }
}
