<?php


namespace QX\Forms\DI;


use Nette\DI\CompilerExtension;

class FormsExtension extends CompilerExtension
{

    public function loadConfiguration()
    {

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('baseForm'))
            ->setImplement('App\Forms\IBaseFormFactory')
            ->setInject(TRUE);

    }

} 