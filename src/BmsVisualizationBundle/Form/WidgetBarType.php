<?php

namespace BmsVisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use BmsVisualizationBundle\Form\ColorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class WidgetBarType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('visibility', CheckboxType::class, array(
                    'label' => 'Wyświetlać panel?',
                    'required' => false
                ))
                ->add('tooltip', CheckboxType::class, array(
                    'label' => 'Wyświetlać podpowiedź?',
                    'required' => false
                ))
                ->add('topPosition', IntegerType::class, array(
                    'label' => 'Od góry',
                    'required' => true
                ))
                ->add('leftPosition', IntegerType::class, array(
                    'label' => 'Od lewej',
                    'required' => true
                ))
                ->add('width', IntegerType::class, array(
                    'label' => 'Wysokość',
                    'required' => true
                ))
                ->add('height', IntegerType::class, array(
                    'label' => 'Szerokość',
                    'required' => true
                ))
                ->add('zIndex', HiddenType::class, array(
                    'required' => true
                ))
                ->add('displayPrecision', ChoiceType::class, array(
                    'choices' => array(
                        '0' => 0,
                        '0,0' => 1,
                        '0,00' => 2,
                        '0,000' => 3,
                    ),
                    'label' => null,
                    'required' => true
                ))
                ->add('backgroundColor', ColorType::class, array(
                    'label' => 'Kolor tła',
                    'required' => false
                ))
                ->add('border', HiddenType::class)
                ->add('borderRadius', HiddenType::class)
                ->add('textAlign', HiddenType::class)
                ->add('fontWeight', HiddenType::class)
                ->add('textDecoration', HiddenType::class)
                ->add('fontStyle', HiddenType::class)                
                ->add('fontFamily', ChoiceType::class, array(
                    'choices' => array(
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
                    ),
                    'label' => null,
                    'required' => false
                ))
                ->add('fontColor', ColorType::class, array(
                    'label' => 'Kolor czcionki',
                    'required' => false
                ))
                ->add('fontSize', IntegerType::class, array(
                    'label' => 'Rozmiar czcionki',
                    'required' => false,
                    'attr' => array( 'min' => 6,
                                     'max' => 96
                            )
                ))
                ->add('contentSource', HiddenType::class)
                ->add('page', EntityType::class, array(
                    'class' => 'BmsVisualizationBundle:Page',
                    'choice_label' => 'name',
                    'required' => false,
                    'empty_data'  => 'brak',
                    'label' => 'Strona'
                ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BmsVisualizationBundle\Entity\Panel'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix() {
        return 'panel';
    }

}
