<?php
namespace VisualizationBundle\Form\DataTransformer;

use BmsConfigurationBundle\Entity\Register;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RegisterToNameDescriptionTransformer implements DataTransformerInterface{
    
    private $manager;
    
    public function __construct(ObjectManager $manager) {
        $this->manager = $manager;
    }
    
    /**
     * Transforms an object (register) to a string (id).
     *
     * @param  Register|null $register
     * @return string
     */
    public function transform($register)
    {
        if (null === $register) {
            return '';
        }

        return $register->getName() . " - " . $register->getDescription();
    }
    
    /**
     * Transforms a string (id) to an object (register).
     *
     * @param  string $registerString
     * @return Register|null
     * @throws TransformationFailedException if object (register) is not found.
     */
    public function reverseTransform($registerString)
    {
        $registerData = explode(" , ", $registerString);
        $registerName = $registerData[0];
        // no register number? It's optional, so that's ok
        if (!$registerName) {
            return;
        }

        $register = $this->manager
            ->getRepository('BmsConfigurationBundle:Register')
            // query for the issue with this id
            ->findOneBy(['name' => $registerName])
        ;

        if (null === $register) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Rejestr "%s" nie istnieje!',
                $registerName
            ));
        }

        return $register;
    }
    
}