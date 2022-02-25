<?php

use Adianti\Database\TRecord;

class Pacientes extends TRecord
{
    const TABLENAME = 'pacientes';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('data_nascimento');
        parent::addAttribute('nome');
        parent::addAttribute('usuario_id');
    }

    public function get_usuarios()
    {
        return Usuarios::find($this->usuario_id);
    }
}