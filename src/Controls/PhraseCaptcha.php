<?php


namespace QX\Forms\Controls;

use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;

class PhraseCaptcha extends TextInput
{

    private $answer;


    public function __construct($label = NULL, $answer = NULL)
    {
        parent::__construct($label);
        $this->answer = $answer;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function getControl()
    {
        $this->addRule(Form::EQUAL, "Chybná odpověď na antispamovou otázku.", $this->answer);

        return parent::getControl();
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }


}