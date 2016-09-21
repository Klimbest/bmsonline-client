<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//FORM TYPES
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GadgetChartType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa'
            ])
            ->add('source', EntityType::class, [
                'label' => 'Zmienna',
                'class' => 'BmsConfigurationBundle:Register',
                'attr' => [
                    'data-live-search' => true
                ]
            ])
            ->add('tooltip', CheckboxType::class, [
                'label' => 'Podpowiedź?',
                'required' => false
            ])
            //POZYCJA
            ->add('topPosition', IntegerType::class, [
                'label' => 'Od góry',
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('leftPosition', IntegerType::class, [
                'label' => 'Od lewej',
                'attr' => [
                    'min' => 0
                ]
            ])
            //ROZMIAR
            ->add('width', IntegerType::class, [
                'label' => 'Szerokość',
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Wysokość',
                'attr' => [
                    'min' => 0
                ]
            ])
            //Kolor serii
            ->add('color', ColorType::class, [
                'label' => 'Kolor serii'
            ])
            //Tło
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Kolor',
                'attr' => [
                    'oninput' => "updateBackgroundColor('variable')"
                ]
            ])
            ->add('backgroundOpacity', RangeType::class, [
                'label' => 'Przezroczystość',
                'attr' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'oninput' => "updateBackgroundColor('variable')"
                ]
            ])
            ->add('hourOffset', IntegerType::class, [
                'label' => 'Od ilu godzin'
            ])
            ->add('const', NumberType::class, [
                'scale' => 2,
                'label' => 'Stała',
            ])
            //UKRYTE
            ->add('zIndex', HiddenType::class);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\GadgetChart'
        ));
    }
}
