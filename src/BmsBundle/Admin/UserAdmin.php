<?php

namespace BmsBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends AbstractAdmin {

    protected $datagridValues = array(
        '_page' => 1
    );

    protected function configureFormFields(FormMapper $formMapper) {
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $rolesChoices = self::flattenRoles($roles);
        
        $imageFieldOptions = array(
            'choices' => $rolesChoices,
            'multiple' => true,
            'expanded' =>true
        );
        
        $formMapper->add('username', 'text')
                ->add('enabled', 'checkbox', array(
                    'required' => false
                ))
                ->add('locked', 'checkbox', array(
                    'required' => false
                ))
                ->add('roles', 'choice', $imageFieldOptions);
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->add('id')
                ->addIdentifier('username')
                ->add('enabled')
                ->add('locked')
                ->add('last_login', 'datetime');
    }

    public function getDashboardActions() {
        $actions = parent::getDashboardActions();

        unset($actions['create']);

        return $actions;
    }

    protected static function flattenRoles($rolesHierarchy) {
        $flatRoles = array();
        foreach ($rolesHierarchy as $roles) {

            if (empty($roles)) {
                continue;
            }

            foreach ($roles as $role) {
                if (!isset($flatRoles[$role])) {
                    $flatRoles[$role] = $role;
                }
            }
        }

        return $flatRoles;
    }

}
