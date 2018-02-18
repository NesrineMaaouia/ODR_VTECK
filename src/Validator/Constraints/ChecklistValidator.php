<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ChecklistValidator
 */
class ChecklistValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $entity = $this->verifyInList($value, $constraint->entity, $constraint->field, $constraint->filter);
        if ($value && ((!$entity && $constraint->isWhiteList) || ($entity && $constraint->isBlackList))) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    /**
     * Verify in list.
     *
     * @param string $value
     * @param string $entityClass
     * @param string $entityField
     * @param array  $filter
     *
     * @return null|object
     */
    private function verifyInList($value, $entityClass, $entityField, array $filter)
    {
        return $this->entityManager
            ->getRepository($entityClass)
            ->findOneBy(array_merge([$entityField => $value], $filter));
    }
}