<?php

namespace SumoCoders\FrameworkCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class ExceptionController extends Controller
{
    public function onExceptionAction()
    {
        return $this->render('@SumoCodersFrameworkCore/Exception/error.html.twig');
    }
}
