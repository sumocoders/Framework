<?php

namespace SumoCoders\FrameworkCoreBundle\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsData
 *
 * @package SumoCoders\FrameworkCoreBundle\Service
 */
class JsData extends ParameterBag
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->handleRequestStack();
    }

    /**
     * Handle the request stack
     */
    protected function handleRequestStack()
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest) {
            $requestData = array(
                'locale' => $currentRequest->getLocale(),
            );

            $this->set('request', $requestData);
        }
    }

    /**
     * Parse into string
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->all());
    }
}
