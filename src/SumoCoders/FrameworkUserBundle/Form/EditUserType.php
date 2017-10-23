<?php

namespace SumoCoders\FrameworkUserBundle\Form;

use SumoCoders\FrameworkMultiUserBundle\Form\EditBaseUserType;
use SumoCoders\FrameworkUserBundle\DataTransferObject\UserDataTransferObject;
use Symfony\Component\Form\FormBuilderInterface;

final class EditUserType extends EditBaseUserType
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
