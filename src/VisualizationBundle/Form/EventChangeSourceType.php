<?php

namespace VisualizationBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventChangeSourceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termSource', EntityType::class, [
                'label' => false,
                'class' => 'BmsConfigurationBundle:Register',
                'attr' => [
                    'data-live-search' => true
                ]
            ])
            ->add('termSign', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '=' => '=',
                    '!=' => '!=',
                    '>' => '>',
                    '>=' => '>=',
                    '<' => '<',
                    '<=' => '<=',
                ]
            ])
            ->add('termValue', NumberType::class, [
                'scale' => 2,
                'label' => false,
                'attr' => [
                    'step' => 0.01
                ]
            ])
            ->add('panelImageSource', HiddenType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\EventChangeSource'
        ));
    }
}
