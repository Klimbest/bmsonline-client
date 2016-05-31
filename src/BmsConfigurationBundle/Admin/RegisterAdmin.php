<?php

namespace BmsConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class RegisterAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->add('name');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->add('id')
                ->addIdentifier('name')
                ->add('registerAddress')
                ->add('function')
                ->add('registerSize')
                ->add('description')
                ->add('description2')
                ->add('modificatorRead')
                ;
    }
       

}
