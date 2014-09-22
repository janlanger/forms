<?php

namespace QX\Forms;

class ClassNotFoundException extends \LogicException {

    public static function packageNotInstalled ($package, $control)
    {
        throw new self("You need to install $package package to be able to use $control control.");
    }
}
 