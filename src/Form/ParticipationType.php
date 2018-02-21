<?php

namespace App\Form;

use App\Entity\Enseigne;
use App\Entity\Participation;
use App\Entity\Products;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Valid;
use App\Form\ShippingType;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
   /*$products = array();
        foreach ($options['products'] as $p) {
            $numeoreference = $p->getNumeoreference();
            $image = $p->getImage();
            $products[$image] = $numeoreference;
        }*/
      //  dump($products);die;

        $builder

            ->add('enseigne', EntityType::class, array(
                'class' => Enseigne::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.enseignename', 'ASC');
                },
                'choice_label' => 'enseignename',
            ))
            ->add('user', UserType::class, [
                'constraints' => new Valid()
            ])
            ->add('eanProduct')
            ->add('eanProduct2')
            ->add('datePurchased', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
            ])
            ->add('product', ShippingType::class, array(
                
            ));
           /* ->add('product', ChoiceType::class, array(
                'choices' => array($products),

                'multiple' => false,
                'expanded' => true,
            ));*/


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Participation::class,
            'products' => null
        ));
    }

    public function getBlockPrefix()
    {
        return 'form_participation';
    }
}
