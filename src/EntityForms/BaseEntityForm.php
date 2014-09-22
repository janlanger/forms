<?php


namespace QX\Forms\EntityForms;

use QX\Forms\BaseForm;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nette\Forms\Controls\SubmitButton;

class BaseEntityForm extends BaseForm
{

    use BaseEntityFormTrait;

}

interface IBaseEntityFormFactory
{
    /** @return \App\Forms\EntityForms\BaseEntityForm */
    public function create();
}