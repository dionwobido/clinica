<?php

use Adianti\Database\TRecord;

class Especialidades extends TRecord
{
    const TABLENAME = 'especialidades';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('descricao');
        parent::addAttribute('titulo');
    }
}