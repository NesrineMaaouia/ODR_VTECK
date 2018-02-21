<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Products;

class ShippingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'Standard Shipping' => 'standard',
                'Expedited Shipping' => 'expedited',
                'Priority Shipping' => 'priority',
            ),
        ));
        // $resolver->setDefaults(array(
        //     'class' => Products::class,
        //     'property' => 'name',
        //     'query_builder' => function(EntityRepository $er) {
        //         return $er->createQueryBuilder('p')
        //             ->orderBy('p.productname', 'ASC');;
        //     },
        //     'expanded' => true,
        //     'multiple' => false
        // ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}