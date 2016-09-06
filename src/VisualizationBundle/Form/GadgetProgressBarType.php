<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//FORM TYPES
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class GadgetProgressBarType extends AbstractType
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
            ->add('tooltip', CheckboxType::class, [
                'label' => 'Wyświetlać podpowiedź?',
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
            ->add('rangeMin', IntegerType::class, [
                'label' => 'Zakres min'
            ])
            ->add('rangeMax', IntegerType::class, [
                'label' => 'Zakres max'
            ])
            ->add('optimumMin', IntegerType::class, [
                'label' => 'Optimum min'
            ])
            ->add('optimumMax', IntegerType::class, [
                'label' => 'Optimum max'
            ])
            ->add('color1', ColorType::class)
            ->add('color2', ColorType::class)
            ->add('color3', ColorType::class)
            ->add('setRegisterId')
            ->add('valueRegisterId');
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
