<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as ODRAssert;

/**
 * Class SuiviType
 */
class SuiviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Assert\Email(['message' => 'L\'email est incorrect.']),
                ]
            ])
            ->add('suivi', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La référence est obligatoire.']),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'constraints' => [
                new ODRAssert\EmailParticipationExist()
            ],
        ));
    }

    public function getBlockPrefix()
    {
        return 'odrbundle_suivi_type';
    }
}
