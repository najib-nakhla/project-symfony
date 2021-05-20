<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\BrandSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('marque',EntityType::class,['class' => Marque::class,
        'choice_label' => 'nom' ,
        'label' => 'Marque' ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BrandSearch::class,
        ]);
    }
}
