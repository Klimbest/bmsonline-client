<?php

namespace BmsConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DeviceAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->add('name')
                ->add('description')
                ->add('modbusAddress');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('name')
                ->add('description')
                ->add('modbusAddress');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('name')
                ->add('description')
                ->add('modbusAddress')
                ->add('_action', null, array(
                            'actions' => array(
                                'show' => array(),
                                'edit' => array(),
                                'delete' => array(),
                                )
                    ))
                ;
    }
       

}
