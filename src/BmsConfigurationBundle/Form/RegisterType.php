<?php

namespace BmsConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegisterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('register_address', TextType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 4),
                    'label' => 'Adres rejestru'
                ))
                ->add('read_function', ChoiceType::class, array(
                    'choices' => array('01 - Discrete Output Coils' => '01',
                        '02 - Discrete Input Contacts' => '02',
                        '03 - Analog Output Holding Registers' => '03',
                        '04 - Analog Input Registers' => '04'),
                    'label' => 'Funkcja odczytu modbus',
                    'attr' => array('disabled' => 'disabled'),
                ))
                ->add('write_function', ChoiceType::class, array(
                    'choices' => array('05 - Force Single Coil' => '05',
                        '06 - Preset Single Register' => '06'),
                    'label' => 'Funkcja zapisu modbus',
                    'attr' => array('disabled' => 'disabled'),
                ))
                ->add('register_size', ChoiceType::class, array(
                    'choices' => array('8' => 8, '16' => 16, '32' => 32),
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Rozmiar rejestru (8, 16, 32)',
                    'required' => false
                ))
                ->add('name', TextType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 16),
                    'label' => 'Nazwa skrócona (max 20 znaków)'
                ))
                ->add('description', TextareaType::class, array(
                    'attr' => array('disabled' => 'disabled', 'maxlength' => 255),
                    'label' => 'Pełna nazwa (max 255 znaków)'
                ))
                ->add('description2', TextareaType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Dodatkowy opis',
                    'required' => false
                ))
                ->add('modificator_read', NumberType::class, array(
                    'scale' => 6,
                    'attr' => array('disabled' => 'disabled', 'step' => 0.000001),
                    'label' => 'Sposób przeliczania danych przy odczycie'
                ))
                ->add('modificator_write', NumberType::class, array(
                    'scale' => 6,
                    'attr' => array('disabled' => 'disabled', 'step' => 0.000001),
                    'label' => 'Sposób przeliczania danych przy zapisie'
                ))
                ->add('archive_register', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Archiwizować rejestr?',
                    'required' => false,
                ))
                ->add('active_register', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Odczytywać rejestr?',
                    'required' => false
                ))
                ->add('alarm_register', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Rejestr alarmowy?',
                    'required' => false
                ))
                ->add('write_register', CheckboxType::class, [
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Rejestr do zapisu?',
                    'required' => false
                    ])
                ->add('bit_register', CheckboxType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Rejestr bitowy?',
                    'required' => false
                ))
                ->add('bit_registers', CollectionType::class, array(
                    'entry_type' => BitRegisterType::class,
                    'allow_add' => true,
                    'label' => false
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
