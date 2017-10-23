<?php

namespace SumoCoders\FrameworkUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;
use SumoCoders\FrameworkMultiUserBundle\Security\PasswordResetToken;

/**
 * @ORM\Entity(repositoryClass="SumoCoders\FrameworkUserBundle\Repository\SumoUserRepository")
 * @ORM\Table()
 */
class SumoUser extends BaseUser
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
        return ['ROLE_USER'];
    }
}
