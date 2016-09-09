<?php

namespace VisualizationBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EventLinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventLink', EntityType::class, [
                'class' => 'VisualizationBundle:EventLink',
                'label' => 'Po kliknięciu przenieś do strony',
                'empty_data' => null,
                'required' => false
            ]);
    }


}
