<?php

namespace SumoCoders\FrameworkUserBundle\Form;

use SumoCoders\FrameworkMultiUserBundle\Form\AddUserType as FrameworkAddUserType;
use SumoCoders\FrameworkUserBundle\DataTransferObject\UserDataTransferObject;
use Symfony\Component\Form\FormBuilderInterface;

final class AddUserType extends FrameworkAddUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->remove('userName');
    }

    public static function getDataTransferObjectClass(): string
    {
        return UserDataTransferObject::class;
    }

    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
