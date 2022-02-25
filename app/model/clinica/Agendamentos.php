<?php

use Adianti\Database\TRecord;

class Agendamentos extends TRecord
{
    const TABLENAME = 'agendamentos';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    //METODO CONSTRUCT
    public function __construct($id = NULL, $callObjectload = TRUE)
    {
        parent::__construct($id, $callObjectload);
        parent::addAttribute('data_consulta');
        parent::addAttribute('especialidade_id');
        parent::addAttribute('horario_id');
        parent::addAttribute('medico_id');
        parent::addAttribute('paciente_id');
    }

    public function get_especialidades()
    {
        return Especialidades::find($this->especialidade_id);
    }

    public function get_horarios()
    {
        return Horas::find($this->horario_id);
    }

    public function get_medicos()
    {
        return Medicos::find($this->medico_id);
    }

    public function get_pacientes()
    {
        return Pacientes::find($this->paciente_id);
    }

}