<?php

use Adianti\Database\TRecord;

class UsuariosTemPerfis extends TRecord
{
    const TABLENAME = 'usuarios_tem_perfis';
    //const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($callObjectload = TRUE)
    {
        parent::__construct($callObjectload);
        parent::addAttribute('usuario_id');
        parent::addAttribute('perfil_id');
    }
}