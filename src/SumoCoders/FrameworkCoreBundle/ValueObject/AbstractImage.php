<?php

namespace SumoCoders\FrameworkCoreBundle\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * The following things are mandatory to use this class.
 *
 * You need to implement the method getUploadDir.
 * When using this class in an entity certain life cycle callbacks should be called
 * prepareToUpload for @ORM\PrePersist() and @ORM\PreUpdate()
 * upload for @ORM\PostPersist() and @ORM\PostUpdate()
 * remove for @ORM\PostRemove()
 *
 * The following things are optional
 * A fallback image can be set by setting the full path of the image to the FALLBACK_IMAGE constant
 */
abstract class AbstractImage extends AbstractFile
{
    /**
     * @var string|null
     */
    const FALLBACK_IMAGE = null;

    /**
     * @return string
     */
    public function getWebPath()
    {
        $webPath = parent::getWebPath();

        if (empty($webPath)) {
            return static::FALLBACK_IMAGE;
        }

        return $webPath;
    }

    /**
     * @return null|string
     */
    public function getFallbackImage()
    {
        return static::FALLBACK_IMAGE;
    }
}
