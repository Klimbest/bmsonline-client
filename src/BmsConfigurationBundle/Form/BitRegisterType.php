<?php

namespace BmsConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use BmsConfigurationBundle\Form\DataTransformer\RegisterToIdTransformer;

class BitRegisterType extends AbstractType{
    
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        
        $builder->add('name', HiddenType::class)
                ->add('description', TextType::class, array(
                    'attr' => array('disabled' => 'disabled'),
                    'required' => false,
                    'label' => false 
                    ))
                ->add('bitValue', HiddenType::class)
                ->add('bitPosition', HiddenType::class)
                ->add('register', HiddenType::class);
        
        $builder->get('register')
                ->addModelTransformer(new RegisterToIdTransformer($this->manager));
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