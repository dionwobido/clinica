<?php

use Adianti\Database\TRecord;

class MedicosTemEspecialidades extends TRecord
{
    const TABLENAME = 'medicos_tem_especialidades';
    //const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($callObjectload = TRUE)
    {
        parent::__construct($callObjectload);
        parent::addAttribute('especialidade_id');
        parent::addAttribute('medico_id');
    }
}