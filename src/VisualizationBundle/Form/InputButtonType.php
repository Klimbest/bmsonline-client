<?php

namespace VisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//FORM TYPES
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class InputButtonType extends AbstractType
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
            ->add('destination', EntityType::class, [
                'label' => 'Zmienna',
                'class' => 'BmsConfigurationBundle:Register',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('r')
                        ->where('r.writeRegister = 1');
                },
                'attr' => [
                    'data-live-search' => true
                ]
            ])
            ->add('source', HiddenType::class)
            ->add('value', NumberType::class, [
                'scale' => 2,
                'label' => 'Wysyłana wartość',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisualizationBundle\Entity\InputButton'
        ));
    }
}
