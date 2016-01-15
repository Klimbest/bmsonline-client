<?php

namespace BmsConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RegisterType extends AbstractType{
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $defaultvalue = $options['data']->getRegisterAddress();
        $builder->add('register_address', TextType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 4),
                    'label' => 'Adres rejestru'
                    ))
                ->add('scan_queue', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled', 'min' => 1, 'max' => 5),
                    'label' => 'Kolejka odczytu danych(od 1 do 5)'
                    ))
                ->add('name', TextType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 16),
                    'label' => 'Nazwa skrócona (max 16 znaków)'
                    ))
                ->add('description', TextareaType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 255),
                    'label' => 'Pełna nazwa (max 255 znaków)'
                    ))
                ->add('display_suffix', TextType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Grupa danych',
                    'required' => false
                    ))
                ->add('modificator_read', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Sposób przeliczania danych przy odczycie'
                    ))
                ->add('modificator_write', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Sposób przeliczania danych przy zapisie',
                    'required' => false
                    ))
                ->add('archive', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Archiwizować rejestr?',
                    'required' => false,
                    ))
                ->add('active', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Odczytywać rejestr?',
                    'required' => false
                    ));
    }
    
    
    /**
     * @param OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BmsConfigurationBundle\Entity\Register'
        ));
    }
    
    /**
     * 
     * @return string
     */
    public function getBlockPrefix() {
        return 'bmsconfigurationbundle_register';
    }

}