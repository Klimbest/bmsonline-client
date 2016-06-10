<?php

namespace BmsConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommunicationTypeAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->add('name')
                ->add('type')
                ->add('baudRate')
                ->add('parity')
                ->add('dataBits')
                ->add('stopBits')
                ->add('timeoutResponse')
                ->add('timeoutBetweenSend')
                ->add('timeoutBeforeScan');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->add('id')
                ->addIdentifier('name')
                ->add('type')
                ->add('baudRate')
                ->add('parity')
                ->add('dataBits')
                ->add('stopBits')
                ->add('timeoutResponse')
                ->add('timeoutBetweenSend')
                ->add('timeoutBeforeScan')
                ->add('updated')
                ->add('hardware', null, array(), 'entity', array(
                    'class'    => 'BmsConfigurationBundle\Entity\Hardware',
                    'choice_label' => 'name',
                ))
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
