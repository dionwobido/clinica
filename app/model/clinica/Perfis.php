<?php

use Adianti\Database\TRecord;

class Perfis extends TRecord
{
    const TABLENAME = 'perfis';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('descricao');
    }
}