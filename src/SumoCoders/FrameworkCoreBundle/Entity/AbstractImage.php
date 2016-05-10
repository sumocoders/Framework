<?php

namespace SumoCoders\FrameworkCoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractImage implements JsonSerializable
{
    const FALLBACK_IMAGE = null;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $alt = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path = null;

    /**
     * @var UploadedFile
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "file.invalid_mime_type"
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $oldPath;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $alt
     *
     * @return self
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return $this->path === null
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        $file = $this->getAbsolutePath();
        if (is_file($file) && file_exists($file)) {
            return '/' . $this->getUploadDir() . '/' . $this->path;
        }

        return static::FALLBACK_IMAGE;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    /**
     * the dir in the web folder where the image needs to be uploaded.
     *
     * @return string
     */
    abstract protected function getUploadDir();

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->updatedAt = new DateTime();

        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->oldPath = $this->path;
            $this->path = null;
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $this->updatedAt = new DateTime();

        if ($this->getFile() !== null) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if ($this->getFile() === null) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->oldPath)) {
            // delete the old image
            $oldFile = $this->getUploadRootDir() . '/' . $this->oldPath;
            if (is_file($oldFile) && file_exists($oldFile)) {
                unlink($oldFile);
            }
            // clear the $this->oldPath image path
            $this->oldPath = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if (is_file($file) && file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Returns a string representation of the child.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getWebPath();
    }

    /**
     * @return null|string
     */
    public function getFallbackImage()
    {
        return static::FALLBACK_IMAGE;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'url' => (string) $this->getWebPath(),
            'alt' => $this->getAlt(),
        ];
    }
}
