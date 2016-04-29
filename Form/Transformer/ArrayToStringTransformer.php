<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Transformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class ArrayToStringTransformer implements DataTransformerInterface
{
    protected $em;
    protected $class;
    protected $property;

    public function __construct(EntityManager $em, $class, $property)
    {
        error_log($property);
        $this->em = $em;
        $this->class = $class;
        $this->property = $property;
    }

    /**
     * @param mixed $array
     *
     * @internal param mixed $string
     *
     * @return array
     */
    public function reverseTransform($array)
    {
        if (is_array($array) && array_key_exists(0, $array)) {
            $newIds = [];
            $ids = explode(',', $array[0]);
            $repo = $this->em->getRepository($this->class);
            $objects = $repo->findById($ids);
            foreach ($objects as $object) {
                if (false !== $key = array_search($object->getId(), $ids)) {
                    $newIds[] = $ids[$key];
                    unset($ids[$key]);
                }
            }
            $objectsArray = [];
            foreach ($ids as $objectProperty) {
                $object = new $this->class();
                $object->{'set'.ucfirst($this->property)}($objectProperty);
                $this->em->persist($object);
                $objectsArray[] = $object;
            }
            $this->em->flush();

            foreach ($objectsArray as $objectObj) {
                $newIds[] = $objectObj->getId();
            }

            return $newIds;
        }

        return $array;
    }

    /**
     * @param mixed $array
     *
     * @return string
     */
    public function transform($array)
    {
        return $array;
    }
}
