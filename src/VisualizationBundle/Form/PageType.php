<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//FORM TYPES
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use VisualizationBundle\Form\ColorType;

class PageType extends AbstractType
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
            ->add('width', IntegerType::class, [
                'label' => 'Szerokość',

                'attr' => [
                    'min' => 0,
                    'step' => 1
                ]
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Wysokość',

                'attr' => [
                    'min' => 0,
                    'step' => 1
                ]
            ])
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Kolor tła',

            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\Page'
        ));
    }
}
