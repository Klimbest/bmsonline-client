<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//FORM TYPES
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PanelImageType extends AbstractType
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
            ->add('source', HiddenType::class)
            ->add('tooltip', CheckboxType::class,  [
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
            //RAMKA
            ->add('borderWidth', IntegerType::class, [
                'label' => 'Grubość',
                'attr' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ])
            ->add('borderStyle', ChoiceType::class, [
                'label' => 'Styl',
                'choices' => [
                    "Ciągła" => "solid",
                    "Wykropkowana" => "dotted",
                    "Wykreskowana" => "dashed"
                ]
            ])
            ->add('borderColor', ColorType::class, [
                'label' => 'Kolor'
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
            'data_class' => 'VisualizationBundle\Entity\PanelImage'
        ));
    }
}
