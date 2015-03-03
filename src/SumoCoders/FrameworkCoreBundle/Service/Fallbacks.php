<?php

namespace SumoCoders\FrameworkCoreBundle\Service;

/**
 * Class Fallbacks
 *
 * @package SumoCoders\FrameworkCoreBundle\Service
 */
class Fallbacks extends KeyValueStore
{
    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = null)
    {
        $this->populateFallbacks($parameters);
    }

    /**
     * Populate the array with data, can be called recursive
     *
     * @param mixed|array $data
     * @param null|string $prefix
     */
    private function populateFallbacks($data, $prefix = null)
    {
        // anything to process?
        if (empty($data)) {
            return;
        }

        // loop all data
        foreach ($data as $name => $value) {
            // if it is an array we want to process some more
            if (is_array($value)) {
                // if there is a numeric key inside the keys we will treat
                // the array as an key-value-array, otherwise we will assume
                // the array was intended as an array
                $anyNonNumericKeys = false;
                $keys = array_keys($value);
                foreach ($keys as $key) {
                    if (!is_numeric($key)) {
                        $anyNonNumericKeys = true;
                        break;
                    }
                }

                if ($anyNonNumericKeys) {
                    $this->populateFallbacks($value, $name);
                } else {
                    $this->add($name, $value, $prefix);

                    // we will loop the array and add each item with the index
                    // so we are able to get a single item from an array
                    $prefixChunks = array($prefix, $name);
                    $newPrefix = trim(implode(self::SPLITCHAR, $prefixChunks), self::SPLITCHAR);
                    $this->populateFallbacks($value, $newPrefix);
                }
            } else {
                $this->add($name, $value, $prefix);
            }
        }
    }
}
