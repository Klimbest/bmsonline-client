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
        $formMapper->add('username', 'text')
                ->add('enabled', 'checkbox')
                ->add('locked', 'checkbox')
                ->add('roles');
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

    
    
}
