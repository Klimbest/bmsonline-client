<?php

namespace BmsVisualizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ColorType extends AbstractType {

    public function getParent()
    {
        return TextType::class;
    }

}
