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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PanelVariableType extends AbstractType
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
            ->add('tooltip', CheckboxType::class, [
                'label' => 'Wyświetlać podpowiedź?'
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
            //PRECYZJA WYŚWIETLANIA
            ->add('displayPrecision', ChoiceType::class, [
                'label' => 'Precyzja wyświetlania',
                'choices' => [
                    "0" => 0,
                    "0.0" => 1,
                    "0.00" => 2,
                    "0.000" => 3
                ]
            ])
            //Tło
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Kolor'
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
            //ZAOKRĄGLENIE NAROŻNIKÓW
            ->add('borderRadiusLeftTop', RangeType::class, [
                'label' => 'Lewy górny',
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('borderRadiusLeftBottom', RangeType::class, [
                'label' => 'Lewy dolny',
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('borderRadiusRightTop', RangeType::class, [
                'label' => 'Prawy górny',
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('borderRadiusRightBottom', RangeType::class, [
                'label' => 'Prawy dolny',
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            //CZCIONKA
            ->add('textAlign', HiddenType::class)
            ->add('fontWeight', HiddenType::class)
            ->add('textDecoration', HiddenType::class)
            ->add('fontStyle', HiddenType::class)
            ->add('fontFamily', ChoiceType::class, [
                'label' => 'Styl',
                'choices' => [
                    "Arial" => "Arial",
                    "Black" => "Black",
                    "Century Gothic" => "Century Gothic",
                    "Cochin" => "Cochin",
                    "Comic Sans MS" => "Comic Sans MS",
                    "Courier" => "Courier",
                    "Courier New" => "Courier New",
                    "Garamond" => "Garamond",
                    "Geneva" => "Geneva",
                    "Georgia" => "Georgia",
                    "Helvetica" => "Helvetica",
                    "Lucida" => "Lucida",
                    "Lucida Grande" => "Lucida Grande",
                    "Lucida Sans" => "Lucida Sans",
                    "Lucida Sans Unicode" => "Lucida Sans Unicode",
                    "Monotype Corsiva" => "Monotype Corsiva",
                    "MS Serif" => "MS Serif",
                    "Tahoma" => "Tahoma",
                    "Times New Roman" => "Times New Roman",
                    "Trebuchet MS" => "Trebuchet MS",
                    "Verdana" => "Verdana"
                ]
            ])
            ->add('fontColor', ColorType::class, [
                'label' => 'Kolor'
            ])
            ->add('fontSize', IntegerType::class, [
                'label' => 'Rozmiar',
                'attr' => ['min' => 6,
                    'max' => 96
                ]
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
            'data_class' => 'VisualizationBundle\Entity\PanelVariable'
        ));
    }
}
