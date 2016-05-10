<?php

namespace SumoCoders\FrameworkCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Image.
 *
 * @ORM\Entity
 */
class Image extends AbstractImage
{
    /**
     * {@inheritdoc}
     */
    protected function getUploadDir()
    {
        return 'uploads/images';
    }
}
