<?php

namespace SumoCoders\FrameworkUserBundle\Form;

use SumoCoders\FrameworkMultiUserBundle\Entity\UserRole;
use SumoCoders\FrameworkMultiUserBundle\Form\EditBaseUserType;
use SumoCoders\FrameworkUserBundle\DataTransferObject\UserDataTransferObject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

final class EditUserType extends EditBaseUserType
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    public function __construct(
        TranslatorInterface $translator,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        parent::__construct($translator);

        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->remove('userName');
        $builder->remove('roles');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'roles',
                EntityType::class,
                [
                    'attr' => [
                        'class' => 'select2',
                    ],
                    'class' => UserRole::class,
                    'choice_label' => function (UserRole $userRole) {
                        return $this->translator->trans($userRole);
                    },
                    'required' => true,
                    'multiple' => true,
                    'placeholder' => '',
                ]
            );
        }
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
