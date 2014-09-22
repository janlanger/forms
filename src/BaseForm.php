<?php

namespace QX\Forms;

use Kdyby;
use Nette\Application\UI;
use QX\Forms\Controls\FormControlsTrait;


class BaseForm extends UI\Form
{
    use FormControlsTrait;

    public function injectDependencies(Kdyby\Translation\Translator $translator)
    {
        $this->setTranslator($translator);
    }

}


