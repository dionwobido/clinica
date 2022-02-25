<?php

use Adianti\Database\TRecord;

class Horas extends TRecord
{
    const TABLENAME = 'horas';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('hora_minuto');
    }
}