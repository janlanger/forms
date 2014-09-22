<?php


namespace QX\Forms\EntityForms;


interface IBaseEntityFormFactory
{
    /** @return \App\Forms\EntityForms\BaseEntityForm */
    public function create();
}