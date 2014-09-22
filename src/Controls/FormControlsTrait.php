<?php


namespace QX\Forms\Controls;

use Nextras\Forms;
use QX\Forms\ClassNotFoundException;

/**
 *
 */
trait FormControlsTrait
{


    public function addTypeahead($name, $label = NULL, $callback = NULL)
    {
        if (!class_exists('Nextras\Forms\Controls\Typeahead')) {
            ClassNotFoundException::packageNotInstalled('nextras/forms', 'Typeahead');
        }

        return $this[$name] = new Forms\Controls\Typeahead($label, $callback);
    }

    public function addDatePicker($name, $label = NULL)
    {
        if (!class_exists('Nextras\Forms\Controls\DatePicker')) {
            ClassNotFoundException::packageNotInstalled('nextras/forms', 'DatePicker');
        }

        return $this[$name] = new Forms\Controls\DatePicker($label);
    }

    public function addDateTimePicker($name, $label = NULL)
    {
        if (!class_exists('Nextras\Forms\Controls\DateTimePicker')) {
            ClassNotFoundException::packageNotInstalled('nextras/forms', 'DateTimePicker');
        }

        return $this[$name] = new Forms\Controls\DateTimePicker($label);
    }


    public function addOptionList($name, $label, $items = NULL)
    {
        if (!class_exists('Nextras\Forms\Controls\OptionList')) {
            ClassNotFoundException::packageNotInstalled('nextras/forms', 'OptionList');
        }

        return $this[$name] = new Forms\Controls\OptionList($label, $items);
    }

    public function addMultiOptionList($name, $label = NULL, $items = NULL)
    {
        if (!class_exists('Nextras\Forms\Controls\MultiOptionList')) {
            ClassNotFoundException::packageNotInstalled('nextras/forms', 'MultiOptionList');
        }

        return $this[$name] = new Forms\Controls\MultiOptionList($label, $items);
    }

    public function addPhraseCaptcha($name, $label = NULL, $answer = NULL)
    {
        return $this[$name] = new PhraseCaptcha($label, $answer);
    }


}