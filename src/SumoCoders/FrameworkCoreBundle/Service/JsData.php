<?php

namespace SumoCoders\FrameworkCoreBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsData
 *
 * @package SumoCoders\FrameworkCoreBundle\Service
 */
class JsData extends KeyValueStore
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
            $this->add('request.locale', $currentRequest->getLocale());
        }
    }

    /**
     * Parse into data
     *
     * @return string
     */
    public function parse()
    {
        return json_encode($this->data);
    }
}
