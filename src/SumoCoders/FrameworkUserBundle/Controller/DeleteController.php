<?php

namespace SumoCoders\FrameworkUserBundle\Controller;

use SumoCoders\FrameworkMultiUserBundle\Command\DeleteUserHandler;
use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\BaseUserDataTransferObject;
use SumoCoders\FrameworkMultiUserBundle\Entity\BaseUser;
use SumoCoders\FrameworkMultiUserBundle\Form\DeleteType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

final class DeleteController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var DeleteUserHandler */
    private $handler;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var TranslatorInterface */
    private $translator;

    /** @var Router */
    private $router;

    /** @var string */
    private $redirectRoute;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    public function __construct(
        FormFactoryInterface $formFactory,
        DeleteUserHandler $handler,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator,
        Router $router,
        string $redirectRoute,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->formFactory = $formFactory;
        $this->handler = $handler;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
        $this->router = $router;
        $this->redirectRoute = $redirectRoute;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     * @param BaseUser $user
     *
     * @return array|RedirectResponse
     */
    public function deleteAction(Request $request, BaseUser $user)
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Access denied.');
        }

        $userDataTransferObject = BaseUserDataTransferObject::fromUser($user);

        $form = $this->formFactory->create(DeleteType::class, $userDataTransferObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handle($form->getData());

            $this->flashBag->add(
                'success',
                $this->translator->trans(
                    'sumocoders.multiuserbundle.flash.deleted',
                    ['%user%' => $form->getData()->getEntity()->getDisplayName()]
                )
            );

            return new RedirectResponse($this->router->generate($this->redirectRoute));
        }

        return ['form' => $form->createView()];
    }
}
