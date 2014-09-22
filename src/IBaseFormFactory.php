<?php


namespace QX\Forms;


interface IBaseFormFactory
{
    /** @return BaseForm */
    function create();
}