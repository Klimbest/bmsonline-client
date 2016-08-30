<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanelImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('source')
            ->add('tooltip')
            ->add('topPosition')
            ->add('leftPosition')
            ->add('width')
            ->add('height')
            ->add('zIndex')
            ->add('borderWidth')
            ->add('borderStyle')
            ->add('borderColor')
            ->add('page')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\PanelImage'
        ));
    }
}
