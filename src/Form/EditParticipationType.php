<?php

namespace App\Form;

use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EditParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserType::class, [
                'constraints' => new Valid(),
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('eanProduct')
            ->add('datePurchased', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ])
            ->add('invoice', FileType::class, array(
                'attr' => array(
                    'title' => "Je joins ma preuve d'achat"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Participation::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'form_edit_participation';
    }
}
