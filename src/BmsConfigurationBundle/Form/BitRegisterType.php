<?php

namespace BmsConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BitRegisterType extends AbstractType{
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        
        $builder->add('name', TextType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 16),
                    'label' => false 
                    ))
                ->add('description', TextareaType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 255),
                    'label' => false 
                    ))
                ->add('bitValue', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'required' => false,
                    'label' => false 
                    ));
    }
    
    
    /**
     * @param OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BmsConfigurationBundle\Entity\BitRegister'
        ));
    }
    
    /**
     * 
     * @return string
     */
    public function getBlockPrefix() {
        return 'bmsconfigurationbundle_bitregister';
    }

}