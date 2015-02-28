<?php

namespace SumoCoders\FrameworkCoreBundle\Service;

/**
 * Class KeyValueStore
 *
 * @package SumoCoders\FrameworkCoreBundle\Service
 */
class KeyValueStore
{
    const SPLITCHAR = '.';

    /**
     * @var array
     */
    protected $data = array();

    /**
     * Add a value into the array with a given name
     *
     * @param string      $name
     * @param mixed       $value
     * @param null|string $prefix
     */
    public function add($name, $value, $prefix = null)
    {
        $key = $name;
        if (null !== $prefix) {
            $key = $prefix . self::SPLITCHAR . $name;
        }
        $this->data[$key] = $value;
    }

    /**
     * Get a fallback
     *
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }
}
