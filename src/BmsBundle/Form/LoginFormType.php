<?php

//src/AppBundle/Form/LoginFormType.php

namespace BmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class LoginFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('_csrf_token', HiddenType::class)
                ->add('_username', EmailType::class, array(
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('placeholder' => 'Adres e-mail'
            )))
                ->add('_password', PasswordType::class, array(
                    'label' => 'form.password',
                    'translation_domain' => 'FOSUserBundle',
                    'mapped' => false,
                    'attr' => array('placeholder' => 'Hasło'
            )));
    }

    public function getBlockPrefix() {
        return null;
    }

}
