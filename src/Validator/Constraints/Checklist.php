<?php

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class Checklist
 * @Annotation
 */
class Checklist extends Constraint
{
    public $message = "Cette offre est exclusivement réservée aux nouveaux clients Mon Timbre en Ligne.";
    public $entity = '';
    public $field = 'item';
    public $isBlackList = null;
    public $isWhiteList = null;
    public $filter = [];

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}