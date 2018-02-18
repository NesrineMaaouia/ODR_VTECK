<?php

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class EmailExist
 * @Annotation
 */
class EmailExist extends Constraint
{
    public $message = "Aucune participation n'a été effectuée avec cette adresse email.";

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}