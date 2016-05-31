<?php

namespace BmsConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class HardwareAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->add('id')
                ->add('name')
                ->add('raspiKey')
                ->add('active')
                ->add('connected');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->add('id')
                ->addIdentifier('name')
                ->add('raspiKey')
                ->add('active')
                ->add('connected')
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
