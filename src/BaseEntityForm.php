<?php


namespace App\Forms\EntityForms;

use App\Forms\BaseForm;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nette\Forms\Controls\SubmitButton;

class BaseEntityForm extends BaseForm
{

    private $entity;

    public $onBind = [];
    public $onHandle = [];
    public $onComplete = [];
    /** @var FieldsMapper */
    private $fieldsMapper;
    /** @var EntityMapper */
    private $entityMapper;


    public static function getClassName()
    {
        return get_called_class();
    }

    public function bindEntity($entity)
    {
        $this->entity = $entity;
        $this->fieldsMapper->bindEntity($this, $entity);

        if ($this->onBind) {
            $this->onBind($entity);
        }
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getDefaultHandler()
    {
        return callback($this, 'handler');
    }

    /**
     * @param BaseEntityForm|SubmitButton $form
     * @param $values
     */
    public function handler($form)
    {
        if ($form instanceof SubmitButton) {
            $form = $form->getForm();
        }
        if (!$form->isValid()) {
            return;
        }
        $values = $form->getValues();
        $this->onHandle($this->getEntity(), $values);
        $this->updateEntity($this->getEntity(), $values);

        $this->onComplete($this->entity);
    }

    public function updateEntity($entity, $values)
    {
        $this->entityMapper->updateEntity($entity, $values);
    }

    public function injectClassDependencies(FieldsMapper $fieldMapper, EntityMapper $entityMapper)
    {
        $this->fieldsMapper = $fieldMapper;
        $this->entityMapper = $entityMapper;
    }

}

interface IBaseEntityFormFactory
{
    /** @return \App\Forms\EntityForms\BaseEntityForm */
    public function create();
}