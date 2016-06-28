<?php

namespace BmsConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DeviceType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Nazwa urządzenia (max 16 znaków)'
                ))
                ->add('description', TextareaType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Opis / Pełna nazwa (max 255 znaków)'
                ))
                ->add('modbusAddress', TextType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 3),
                    'label' => 'Adres modbus'
                ))
                ->add('active', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Aktywny',
                    'required' => false
                ))
                ->add('report', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Report Slave',
                    'required' => false
                ))
                ->add('localization', TextareaType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Lokalizacja (max 255 znaków)',
                    'required' => false
                ));
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BmsConfigurationBundle\Entity\Device'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix() {
         return 'bmsconfigurationbundle_device';
    }

}
