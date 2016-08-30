<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanelTextType extends AbstractType
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
            ->add('topPosition')
            ->add('leftPosition')
            ->add('width')
            ->add('height')
            ->add('zIndex')
            ->add('backgroundColor')
            ->add('borderWidth')
            ->add('borderStyle')
            ->add('borderColor')
            ->add('borderRadiusLeftTop')
            ->add('borderRadiusLeftBottom')
            ->add('borderRadiusRightTop')
            ->add('borderRadiusRightBottom')
            ->add('textAlign')
            ->add('fontWeight')
            ->add('textDecoration')
            ->add('fontStyle')
            ->add('fontFamily')
            ->add('fontColor')
            ->add('fontSize')
            ->add('page')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\PanelText'
        ));
    }
}
