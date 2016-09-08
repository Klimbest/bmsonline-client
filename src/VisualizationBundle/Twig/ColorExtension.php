<?php
namespace VisualizationBundle\Twig;

class ColorExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('hexToRgba', array($this, 'hexToRgbaFilter')),
        );
    }

    public function hexToRgbaFilter($color, $opacity = 1)
    {
        $r = substr($color, 1, 2);
        $g = substr($color, 3, 2);
        $b = substr($color, 5, 2);
        return "rgba(" . hexdec($r) . ", " . hexdec($g) . ", " . hexdec($b) . ", " . $opacity . ");";
    }


    public function getName()
    {
        return 'color_extension';
    }

}