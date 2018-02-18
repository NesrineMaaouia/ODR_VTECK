<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Contact;
use App\Entity\Participation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EmailParticipationExistValidator
 */
class EmailParticipationExistValidator extends ConstraintValidator
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
        if ($value instanceof Contact) {
            $email = $value->getEmail();
            $suivi = $value->getReference();
        } else {
            $email = $value['email'];
            $suivi = $value['suivi'];
        }
        if ($email && $suivi && !$this->verifyParticipation($email, $suivi)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{suivi}}', $suivi)
                ->setParameter('{{email}}', $email)
                ->atPath('reference')
                ->addViolation();
        }
    }

    private function verifyParticipation($email, $suivi)
    {
        return $this->entityManager
            ->getRepository(Participation::class)
            ->findOneByEmailAndNum($email, $suivi);
    }
}