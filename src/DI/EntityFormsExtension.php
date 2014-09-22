<?php

namespace QX\Forms\DI;


use Nette\DI\CompilerExtension;

class EntityFormsExtension extends CompilerExtension
{
    public function loadConfiguration()
    {

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('baseForm'))
            ->setImplement('App\Forms\EntityForms\IBaseEntityFormFactory')
            ->setInject(TRUE);

        $builder->addDefinition($this->prefix('fieldMapper'))
            ->setClass('App\Forms\EntityForms\FieldsMapper');

        $builder->addDefinition($this->prefix('entityMapper'))
            ->setClass('App\Forms\EntityForms\EntityMapper');

    }


} 