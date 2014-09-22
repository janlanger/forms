<?php

namespace QX\Forms\EntityForms;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\SubmitButton;
use Nette\Object;

class FieldsMapper extends Object
{
    /** @var EntityManager */
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function bindEntity(Form $form, $entity)
    {
        $metadata = $this->getEntityMetadata($entity);

        $this->doBind($form->getComponents(), $metadata, $entity);
    }

    private function getEntityMetadata($entity)
    {

        return $this->em->getClassMetadata(get_class($entity));
    }

    private function bindElement($name, BaseControl $input, $entity, $metadata)
    {
        $value = $this->getFieldValue($metadata, $name, $entity);

        if ($value instanceof BaseEntity || method_exists($value, 'getId')) {
            $value = $value->getId();
        } elseif (is_array($value) || $value instanceof ArrayCollection || $value instanceof PersistentCollection) {
            $value = array_map(
                function (BaseEntity $entity) {
                    return $entity->getId();
                }, is_object($value) ? $value->toArray() : $value
            );
        }

        $input->setDefaultValue($value);
    }

    private function doBind($components, $metadata, $entity)
    {
        foreach ($components as $name => $input) {
            if($input instanceof SubmitButton) {
                continue;
            } elseif ($input instanceof Container) {
                $value = $this->getFieldValue($metadata, $name, $entity);
                if ($input instanceof \Kdyby\Replicator\Container && is_array($value)) {
                    if (!$input->getForm()->isSubmitted()) {
                        foreach ($value as $item) {
                            $container = $input[$item->id];
                            $this->doBind($container->getComponents(), $this->getEntityMetadata($item), $item);
                        }
                    }
                } elseif (is_object($value)) {
                    $this->doBind($input->getComponents(), $this->getEntityMetadata($value), $value);
                }
                continue;
            }
            $this->bindElement($name, $input, $entity, $metadata);
        }
    }

    private function getFieldValue($metadata, $name, $entity)
    {
        $value = NULL;
        if (isset($metadata->fieldMappings[$name])) {
            $method = "get$name";
        } elseif (isset($metadata->associationMappings[$name])) {
            $method = "get$name";
        } elseif (method_exists($entity, "get$name")) {
            $method = "get$name";
        } elseif (method_exists($entity, "is$name")) {
            $method = "is$name";
        } else {
            return NULL;
        }
        if ($value === NULL) {
            $value = $entity->$method();
        }

        return $value;
    }
}