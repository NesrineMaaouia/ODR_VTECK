<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('civility', ChoiceType::class, [
                'choices' => ['Mme' => '2', 'M' => '1'],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('lastname')
            ->add('firstname')
            ->add('email')
            ->add('reference')
            ->add('object')
            ->add('message');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Contact',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odmquotyfdj_bundle';
    }
}
