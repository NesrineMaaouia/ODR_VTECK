<?php

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class EmailParticipationExist
 * @Annotation
 */
class EmailParticipationExist extends Constraint
{
    public $message = "Aucune participation {{suivi}} n'a été effectuée avec l'adresse email {{email}}.";

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}