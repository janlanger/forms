<?php

namespace QX\Forms\EntityForms;


use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Nette\Object;

class EntityMapper extends Object
{


    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    private function getEntityMetadata($entity)
    {

        return $this->em->getClassMetadata(get_class($entity));
    }

    public function updateEntity($entity, $values)
    {
        $mapping = $this->getEntityMetadata($entity);
        foreach ($values as $key => $value) {
            if (isset($mapping->associationMappings[$key])) {
                $this->doAssociationUpdate($entity, $value, $mapping, $key);
            }
            else {
                $this->updateScalar($entity, $key, $value);
            }

        }
    }

    /**
     * @param $entity
     * @param $value
     * @param $mapping
     * @param $key
     * @param $item
     * @return array
     */
    private function doAssociationUpdate($entity, $value, $mapping, $key)
    {
        if (is_scalar($value)) {
            $value = $this->convertToReference($value, $mapping, $key)[0];
        }
        if (is_array($value) && is_scalar(@reset($value))) {
            $value = $this->convertToReference($value, $mapping, $key);
        }
        if (in_array($mapping->associationMappings[$key]['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY])) {
            $this->updateCollection($entity, $key, $value);
        } else {
            $this->updateScalar($entity, $key, $value);
        }
    }

    /**
     * @param $values
     * @param $mapping
     * @param $key
     * @param $item
     * @return mixed
     */
    private function convertToReference($values, $mapping, $key)
    {
        $references = [];
        foreach ($values as &$item) {
            $references[$item] = $this->em->getReference($mapping->associationMappings[$key]['targetEntity'], $item);
        }

        return $references;
    }

    /**
     * @param $entity
     * @param $field
     * @return array
     */
    private function updateCollection($entity, $field, $value)
    {
        $property = $field;
        if (isset($entity->$field)) {
            //try to inflect the name
            if (substr($field, -3) === 'ies') {
                $field = substr($field, 0, -3) . "y";
            } elseif (substr($field, -1) === 's') {
                $field = substr($field, 0, -1);
            }

        }

        if(!is_array($value)) {
            $value = [$value];
        }

        $existing = [];

        foreach($entity->{'get'.$property}(TRUE) as $key => $item) {
            $existing[$key] = $item->getId();
        }

        $newValues = [];

        foreach($value as $key => $item) {
            $newValues[$key] = $item->getId();
        }
        $toRemove = array_diff($existing, $newValues);
        $toAdd = array_diff($newValues, $existing);

        $existing = array_flip($existing);
        foreach($toRemove as $item) {
            $entity->{'remove'.$field}($entity->{"get".$property}(TRUE)->get($existing[$item]));
        }

        foreach($toAdd as $item) {
            $entity->{'add'.$field}($value[$item]);
        }

    }

    private function updateScalar($entity, $key, $value)
    {
        $entity->{'set'.$key}($value);
    }

} 