<?php

namespace SumoCoders\FrameworkUserBundle\EventListener;

use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Role\SwitchUserRole;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

final class SwitchUserListener
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onSwitchUser(SwitchUserEvent $event): void
    {
        $impersonator = $this->getImpersonatingUser();

        $impersonatingUser = $this->tokenStorage->getToken()->getUser();
        $impersonatedUser = $event->getTargetUser();

        // If a user is impersonating, check if we want to switch back, in that case
        // $user will be the same as the user doing the impersonating.
        if ($impersonator instanceof BaseUser && $impersonator->getId() === $event->getTargetUser()->getId()) {
            $impersonatedUser = $this->tokenStorage->getToken()->getUser();
            $impersonatingUser = $event->getTargetUser();
        }

        if (!$impersonatingUser->canSwitchTo($impersonatedUser)) {
            throw new AccessDeniedException();
        }
    }

    private function getImpersonatingUser(): ?BaseUser
    {
        foreach ($this->tokenStorage->getToken()->getRoles() as $role) {
            if ($role instanceof SwitchUserRole) {
                return $role->getSource()->getUser();
            }
        }

        return null;
    }
}
