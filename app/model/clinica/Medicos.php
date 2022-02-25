<?php

use Adianti\Database\TRecord;

class Medicos extends TRecord
{
    const TABLENAME = 'medicos';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('crm');
        parent::addAttribute('data_inscricao');
        parent::addAttribute('nome');
        parent::addAttribute('usuario_id');
    }

    public function get_usuarios()
    {
        return Usuarios::find($this->usuario_id);
    }
    //DELETA A ESPECIALIDADE DO MEDICO.
    /*
    public function delete($id = null)
    {
        $id = isset($id) ? $id : $this->null;

        MedicosTemEspecialidades::where('medico_id', '=', $this->id)->delete();
        parent::delete($id);
    }*/
}