<?php

namespace SumoCoders\FrameworkUserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SumoCoders\FrameworkMultiUserBundle\Command\Handler;
use SumoCoders\FrameworkMultiUserBundle\Controller\UserController;
use SumoCoders\FrameworkMultiUserBundle\User\Interfaces\UserRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route(service="sumo_coders.user.controller.edit_user")
 */
final class EditController extends UserController
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        FormFactoryInterface $formFactory,
        Router $router,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator,
        string $form,
        Handler $handler,
        UserRepository $userRepository,
        $redirectRoute = null
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;

        parent::__construct(
            $formFactory,
            $router,
            $flashBag,
            $translator,
            $form,
            $handler,
            $userRepository,
            $redirectRoute
        );
    }

    /**
     * @Template("SumoCodersFrameworkMultiUserBundle:User:base.html.twig")
     *
     * @param Request $request
     * @param int|null $id
     *
     * @return array|RedirectResponse
     *
     * @throws AccessDeniedHttpException if not allowed to edit user
     */
    public function editAction(Request $request, ?int $id)
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')
            && $this->tokenStorage->getToken()->getUser()->getId() !== $id
        ) {
            throw new AccessDeniedHttpException('Access denied.');
        }

        return parent::baseAction($request, $id);
    }
}
