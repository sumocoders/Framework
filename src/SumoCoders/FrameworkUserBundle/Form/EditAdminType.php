<?php

namespace SumoCoders\FrameworkUserBundle\Form;

use SumoCoders\FrameworkMultiUserBundle\Form\EditUserType as FrameworkEditUserType;
use SumoCoders\FrameworkUserBundle\DataTransferObject\AdminDataTransferObject;
use SumoCoders\FrameworkUserBundle\DataTransferObject\UserDataTransferObject;
use Symfony\Component\Form\FormBuilderInterface;

final class EditAdminType extends FrameworkEditUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->remove('userName');
    }

    public static function getDataTransferObjectClass(): string
    {
        return AdminDataTransferObject::class;
    }

    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
