<?php

namespace BmsVisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use BmsVisualizationBundle\Form\ColorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PanelType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Nazwa panelu',
            'required' => true,
            'constraints' => [
                new NotBlank()
            ]
        ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Zmienna' => 'variable',
                    'Tekst' => 'text',
                    'Obraz' => 'image'
                ],
                'label' => false,
                'required' => true
            ])
            ->add('visibility', CheckboxType::class, [
                'label' => 'Wyświetlać panel?',
                'required' => false
            ])
            ->add('tooltip', CheckboxType::class, [
                'label' => 'Wyświetlać podpowiedź?',
                'required' => false
            ])
            ->add('topPosition', IntegerType::class, [
                'label' => 'Od góry'
            ])
            ->add('leftPosition', IntegerType::class, [
                'label' => 'Od lewej'
            ])
            ->add('width', IntegerType::class, [
                'label' => 'Szerokość'
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Wysokość'
            ])
            ->add('zIndex', IntegerType::class, [
                'label' => 'Z-index'
            ])
            ->add('displayPrecision', ChoiceType::class, [
                'choices' => [
                    '0' => 0,
                    '0,0' => 1,
                    '0,00' => 2,
                    '0,000' => 3,
                ],
                'label' => false,
                'required' => true
            ])
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Kolor',
                'required' => true
            ])
            ->add('opacity', RangeType::class, [
                'label' => 'Przezroczystość',
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1
                ]
            ])
            ->add('borderWidth', IntegerType::class, [
                'label' => 'Grubość',
                'required' => true
            ])
            ->add('borderStyle', ChoiceType::class, [
                'choices' => [
                    "Ciągła" => "solid",
                    "Wykropkowana" => "dotted",
                    "Wykreskowana" => "dashed"
                ],
                'label' => 'Styl',
                'required' => true
            ])
            ->add('borderColor', ColorType::class, [
                'label' => 'Kolor',
                'required' => true
            ])
            ->add('borderRadiusLeftTop', RangeType::class, [
                'label' => "Lewy górny",
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('borderRadiusLeftBottom', RangeType::class, [
                'label' => "Lewy dolny",
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('borderRadiusRightTop', RangeType::class, [
                'label' => "Prawy górny",
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('borderRadiusRightBottom', RangeType::class, [
                'label' => "Prawy dolny",
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1
                ]
            ])
            ->add('textAlign', HiddenType::class, [
                'required' => false])
            ->add('fontWeight', HiddenType::class)
            ->add('textDecoration', HiddenType::class)
            ->add('fontStyle', HiddenType::class)
            ->add('href', HiddenType::class)
            ->add('fontFamily', ChoiceType::class, [
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
                ],
                'label' => 'Styl',
                'required' => false
            ])
            ->add('fontColor', ColorType::class, [
                'label' => 'Kolor',
                'required' => false
            ])
            ->add('fontSize', IntegerType::class, [
                'label' => 'Rozmiar',
                'required' => false,
                'attr' => ['min' => 6,
                    'max' => 96
                ]
            ])
            ->add('contentSource', HiddenType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BmsVisualizationBundle\Entity\Panel'
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'panel';
    }

}
