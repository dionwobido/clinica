<?php

use Adianti\Database\TRecord;

class Usuarios extends TRecord
{
    const TABLENAME = 'usuarios';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('ativo');
        parent::addAttribute('email');
        parent::addAttribute('senha');
        parent::addAttribute('codigo_verificador');
    }
/*
    public function delete($id = null)
    {
        $id = isset($id) ? $id : $this->null;

        UsuariosTemPerfis::where('usuario_id', '=', $this->id)->delete();
        parent::delete($id);
    }*/
}