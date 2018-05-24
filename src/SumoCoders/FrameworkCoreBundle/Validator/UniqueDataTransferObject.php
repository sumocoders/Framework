<?php

namespace SumoCoders\FrameworkCoreBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;

/**
 * Constraint for the Unique Entity validator.
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueDataTransferObject extends Constraint
{
    const NOT_UNIQUE_ERROR = '23bd9dbf-6b9b-41cd-a99e-4844bcf3077f';

    /** @var string */
    public $message = 'This value is already used.';

    /** @var string */
    public $service = 'unique_data_transfer_object';

    /** @var EntityManagerInterface|null */
    public $em = null;

    /** @var mixed|null */
    public $entityClass = null;

    /** @var string */
    public $repositoryMethod = 'findBy';

    /** @var array */
    public $fields = array();

    /** @var string|null */
    public $errorPath = null;

    /** @var bool */
    public $ignoreNull = true;

    /**
     * @var array
     */
    protected static $errorNames = array(
        self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
    );

    public function getRequiredOptions(): array
    {
        return array('fields');
    }

    /**
     * The validator must be defined as a service with this name.
     *
     * @return string
     */
    public function validatedBy(): string
    {
        return $this->service;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getDefaultOption(): string
    {
        return 'fields';
    }
}
