<?php

namespace QX\Forms\DI;


use Nette\DI\CompilerExtension;

class EntityFormsExtension extends CompilerExtension
{
    public function loadConfiguration()
    {

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('baseForm'))
            ->setImplement('QX\Forms\EntityForms\IBaseEntityFormFactory')
            ->setInject(TRUE);

        $builder->addDefinition($this->prefix('fieldMapper'))
            ->setClass('QX\Forms\EntityForms\FieldsMapper');

        $builder->addDefinition($this->prefix('entityMapper'))
            ->setClass('QX\Forms\EntityForms\EntityMapper');

    }


} 