<?php

namespace SumoCoders\FrameworkCoreBundle\Validator;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Unique Entity Validator checks if one or a set of fields contain unique values.
 */
class UniqueDataTransferObjectValidator extends ConstraintValidator
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param object $dataTransferObject
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     * @throws ConstraintDefinitionException
     */
    public function validate($dataTransferObject, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDataTransferObject) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueDataTransferObject');
        }

        if (!\is_array($constraint->fields) && !\is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if ($constraint->errorPath !== null && !\is_string($constraint->errorPath)) {
            throw new UnexpectedTypeException($constraint->errorPath, 'string or null');
        }

        $fields = (array) $constraint->fields;

        if (\count($fields) === 0) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        if ($dataTransferObject === null) {
            return;
        }

        if ($constraint->em) {
            $em = $this->registry->getManager($constraint->em);

            if (!$em) {
                throw new ConstraintDefinitionException(
                    sprintf('Object manager "%s" does not exist.', $constraint->em)
                );
            }
        } else {
            $em = $this->registry->getManagerForClass(
                $constraint->entityClass ?? \get_class($dataTransferObject->getEntity())
            );

            if (!$em) {
                $em = $this->registry->getManager();
            }
        }

        $class = $em->getClassMetadata($constraint->entityClass ?? \get_class($dataTransferObject->getEntity()));
        /* @var $class \Doctrine\Common\Persistence\Mapping\ClassMetadata */

        $criteria = array();
        $hasNullValue = false;

        foreach ($fields as $fieldName) {
            if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.',
                        $fieldName
                    )
                );
            }

            $fieldValue = $dataTransferObject->$fieldName;

            if ($fieldValue === null) {
                $hasNullValue = true;
            }

            if ($constraint->ignoreNull && $fieldValue === null) {
                continue;
            }

            $criteria[$fieldName] = $fieldValue;

            if ($criteria[$fieldName] !== null && $class->hasAssociation($fieldName)) {
                /* Ensure the Proxy is initialized before using reflection to
                 * read its identifiers. This is necessary because the wrapped
                 * getter methods in the Proxy are being bypassed.
                 */
                $em->initializeObject($criteria[$fieldName]);
            }
        }

        // validation doesn't fail if one of the fields is null and if null values should be ignored
        if ($hasNullValue && $constraint->ignoreNull) {
            return;
        }

        // skip validation if there are no criteria (this can happen when the
        // "ignoreNull" option is enabled and fields to be checked are null
        if (empty($criteria)) {
            return;
        }

        if ($constraint->entityClass !== null) {
            /* Retrieve repository from given entity name.
             * We ensure the retrieved repository can handle the entity
             * by checking the entity is the same, or subclass of the supported entity.
             */
            $repository = $em->getRepository($constraint->entityClass);
            $supportedClass = $repository->getClassName();

            if ($dataTransferObject->getEntity() !== null
                && !$dataTransferObject->getEntity() instanceof $supportedClass) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The "%s" entity repository does not support the "%s" entity. The entity should be an instance of or extend "%s".',
                        $constraint->entityClass,
                        $class->getName(),
                        $supportedClass
                    )
                );
            }
        } else {
            $repository = $em->getRepository(\get_class($dataTransferObject->getEntity()));
        }

        $result = $repository->{$constraint->repositoryMethod}($criteria);

        if ($result instanceof \IteratorAggregate) {
            $result = $result->getIterator();
        }

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof \Iterator) {
            $result->rewind();
        } elseif (\is_array($result)) {
            reset($result);
        }

        /* If no entity matched the query criteria or a single entity matched,
         * which is the same as the entity being validated, the criteria is
         * unique.
         */
        if (\count($result) === 0
            || (
                \count($result) === 1
                && $dataTransferObject->getEntity() === ($result instanceof \Iterator ? $result->current() : current($result))
            )) {
            return;
        }

        $errorPath = $constraint->errorPath ?? $fields[0];
        $invalidValue = $criteria[$errorPath] ?? $criteria[$fields[0]];

        $this->context->buildViolation($constraint->message)
            ->atPath($errorPath)
            ->setParameter('{{ value }}', $this->formatWithIdentifiers($em, $class, $invalidValue))
            ->setInvalidValue($invalidValue)
            ->setCode(UniqueDataTransferObject::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }

    private function formatWithIdentifiers(ObjectManager $em, ClassMetadata $class, $value)
    {
        if (!\is_object($value) || $value instanceof \DateTimeInterface) {
            return $this->formatValue($value, self::PRETTY_DATE);
        }

        if ($class->getName() !== $idClass = \get_class($value)) {
            // non unique value might be a composite PK that consists of other entity objects
            if ($em->getMetadataFactory()->hasMetadataFor($idClass)) {
                $identifiers = $em->getClassMetadata($idClass)->getIdentifierValues($value);
            } else {
                // this case might happen if the non unique column has a custom doctrine type and its value is an object
                // in which case we cannot get any identifiers for it
                $identifiers = array();
            }
        } else {
            $identifiers = $class->getIdentifierValues($value);
        }

        if (!$identifiers) {
            return sprintf('object("%s")', $idClass);
        }

        array_walk(
            $identifiers,
            function (&$id, $field) {
                if (!\is_object($id) || $id instanceof \DateTimeInterface) {
                    $idAsString = $this->formatValue($id, self::PRETTY_DATE);
                } else {
                    $idAsString = sprintf('object("%s")', \get_class($id));
                }

                $id = sprintf('%s => %s', $field, $idAsString);
            }
        );

        return sprintf('object("%s") identified by (%s)', $idClass, implode(', ', $identifiers));
    }
}
