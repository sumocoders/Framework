<?php

namespace SumoCoders\FrameworkUserBundle\Form;

use SumoCoders\FrameworkMultiUserBundle\Form\AddBaseUserType;
use SumoCoders\FrameworkUserBundle\DataTransferObject\AdminDataTransferObject;
use Symfony\Component\Form\FormBuilderInterface;

final class AddAdminType extends AddBaseUserType
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
