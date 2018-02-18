<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as ODRAssert;

/**
 * Class SuiviEmailType
 */
class SuiviEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Assert\Email(['message' => 'L\'email est incorrect.']),
                    new ODRAssert\EmailExist()
                ]
            ]);
    }

    public function getBlockPrefix()
    {
        return 'odrbundle_suivi_email_type';
    }
}
