<?php

namespace SumoCoders\FrameworkUserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\Interfaces\UserDataTransferObject;
use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;
use SumoCoders\FrameworkMultiUserBundle\Entity\UserRole;
use SumoCoders\FrameworkMultiUserBundle\Security\PasswordResetToken;

/**
 * @ORM\Entity(repositoryClass="SumoCoders\FrameworkUserBundle\Repository\UserRepository")
 * @ORM\Table()
 */
class User extends BaseUser
{
    public function __construct(
        string $plainPassword,
        string $displayName,
        string $email,
        Collection $roles,
        int $id = null,
        PasswordResetToken $token = null
    ) {
        parent::__construct($email, $plainPassword, $displayName, $email, $roles, $id, $token);
    }

    public function hasRole(string $roleName): bool
    {
        if (empty($this->roles)) {
            return false;
        }

        /** @var UserRole $role */
        foreach ($this->roles as $role) {
            if ($role->getRole() === $roleName) {
                return true;
            }
        }

        return false;
    }

    public function change(UserDataTransferObject $data): void
    {
        parent::change($data);

        if ($data instanceof UserDataTransferObject) {
            if (is_array($data->getRoles())) {
                $this->roles = new ArrayCollection($data->getRoles());
            } else {
                $this->roles = $data->getRoles();
            }
        }
    }
}
