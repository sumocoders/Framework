<?php

namespace SumoCoders\FrameworkUserBundle\DataTransferObject;

use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\BaseUserDataTransferObject;
use SumoCoders\FrameworkUserBundle\Entity\Admin;
use Symfony\Component\Validator\Constraints as Assert;

final class AdminDataTransferObject extends BaseUserDataTransferObject
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
     * @var Admin
     */
    protected $user;

    public function getEntity(): Admin
    {
        if ($this->user) {
            $this->user->change($this);

            return $this->user;
        }

        return new Admin(
            $this->plainPassword,
            $this->displayName,
            $this->email
        );
    }
}
