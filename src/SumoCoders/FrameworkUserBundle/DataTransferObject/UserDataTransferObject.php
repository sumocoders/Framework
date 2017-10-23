<?php

namespace SumoCoders\FrameworkUserBundle\DataTransferObject;

use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\BaseUserDataTransferObject;
use SumoCoders\FrameworkUserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

final class UserDataTransferObject extends BaseUserDataTransferObject
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="forms.not_blank")
     */
    public $displayName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="forms.not_blank")
     * @Assert\Email(message="forms.invalid_email")
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="forms.not_blank", groups={"add"})
     */
    public $plainPassword;

    /**
     * @var User
     */
    protected $user;

    public function getEntity(): User
    {
        if ($this->user) {
            $this->user->change($this);

            return $this->user;
        }

        return new User(
            $this->plainPassword,
            $this->displayName,
            $this->email
        );
    }
}
