<?php

namespace SumoCoders\FrameworkCoreBundle\Service;

use Symfony\Component\HttpFoundation\ParameterBag;

class DottedParameterBag extends ParameterBag
{
    /**
     * @inheritdoc()
     */
    public function get($path, $default = null, $deep = false)
    {
        $parts = explode('.', $path);

        if (count($parts) > 1) {
            $deep = true;
            $path = array_shift($parts);

            foreach ($parts as $part) {
                $path .= '[' . $part . ']';
            }
        }

        return parent::get($path, $default, $deep);
    }
}
