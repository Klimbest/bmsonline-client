<?php

namespace BmsConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommunicationTypeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', TextType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'label' => 'Nazwa interfejsu'
                ))->add('type', ChoiceType::class, array(
                    'choices' => array('RTU' => 'RTU'),
                    'label' => 'Typ transmisji danych',
                    'attr' => array('disabled' => 'disabled'),
                ))->add('baudRate', ChoiceType::class, array(
                    'choices' => array(
                                        1200 => '1200', 1800 => '1800', 2400 => '2400', 4800 => '4800', 7200 => '7200', 9600 => '9600', 
                                        14400 => '14400', 19200 => '19200', 38400 => '38400', 57600 => '57600', 115200 => '115200'),
                    'label' => 'Szybkość transmisji',
                    'attr' => array('disabled' => 'disabled'),
                ))->add('parity', ChoiceType::class, array(
                    'choices' => array( 'Brak' => 0, 'Nieparzyste' => 1, 'Parzyste' => 2),
                    'label' => 'Parzystość',
                    'attr' => array('disabled' => 'disabled'),
                ))->add('dataBits', ChoiceType::class, array(
                    'choices' => array(5 => 5, 6 => 6, 7 => 7, 8 => 8),
                    'label' => 'Bity Danych',
                    'attr' => array('disabled' => 'disabled'),
                ))->add('stopBits', ChoiceType::class, array(
                    'choices' => array(1 => 1, 2 => 2),
                    'label' => 'Bity Stopu',
                    'attr' => array('disabled' => 'disabled'),
                ))->add('timeoutResponse', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled', 'step' => 1, 'max' => 10000, 'min' => 300),
                    'label' => 'Timeout oczekiwania na odpowiedź od urządzenia (ms)'
                ))->add('timeoutBetweenSend', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled', 'step' => 1, 'max' => 10000, 'min' => 25),
                    'label' => 'Timeout pomiędzy zapytaniami o rejestry (ms)'
                ))->add('timeoutBeforeScan', IntegerType::class, array(
                    'attr' => array('disabled' => 'disabled', 'step' => 1, 'max' => 999, 'min' => 1),
                    'label' => 'Timeout pomiędzy czytaniem z interfejsu (s)'
                ))->add('ipAddress', TextType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'required' => false
                ))->add('port', TextType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'required' => false
                ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BmsConfigurationBundle\Entity\CommunicationType'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix() {
        return 'appbundle_communicationtype';
    }

}
