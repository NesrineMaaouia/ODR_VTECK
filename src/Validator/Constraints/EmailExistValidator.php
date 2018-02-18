<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EmailExistValidator
 */
class EmailExistValidator extends ConstraintValidator
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
        if ($value && !$this->verifyEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    private function verifyEmail($email)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }
}