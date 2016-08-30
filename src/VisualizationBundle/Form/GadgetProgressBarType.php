<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GadgetProgressBarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('rangeMin')
            ->add('rangeMax')
            ->add('optimumMin')
            ->add('optimumMax')
            ->add('color1')
            ->add('color2')
            ->add('color3')
            ->add('setRegisterId')
            ->add('valueRegisterId')
            ->add('page')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\GadgetProgressBar'
        ));
    }
}
