<?php

namespace SumoCoders\FrameworkUserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SumoCoders\FrameworkMultiUserBundle\User\UserRepository;

/**
 * @Route(service="sumo_coders.user.controller.index")
 */
final class IndexController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/user")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     *
     * @return array
     */
    public function indexAction(): array
    {
        return ['users' => $this->userRepository->findBy([], ['username' => 'ASC'])];
    }
}
