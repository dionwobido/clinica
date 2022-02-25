<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;

class AgendamentosForm extends TPage
{
    protected $form;

    use \Adianti\Base\AdiantiStandardFormTrait;

    function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        $this->setAfterSaveAction(new TAction(['AgendamentosList', 'onReload'], ['register_state' => 'true']) );

        $this->setDatabase('db_clinica');
        $this->setActiveRecord('Agendamentos');

        $this->form = new BootstrapFormBuilder('form_Agendamentos');
        $this->form->setFormTitle('Agendamentos');
        $this->form->setClientValidation(true);
        $this->form->setColumnClasses(2, ['col-sm-5 col-lg-4', 'col-sm-7 col-lg-8']);

        $id = new TEntry('id');
        $data_consulta = new TDate('data_consulta');
        $especialidade_id = new TDBCombo('especialidade_id','db_clinica', 'Especialidades', 'id', 'titulo');
        $horario_id = new TDBCombo('horario_id','db_clinica', 'Horas', 'id', 'hora_minuto');
        $medico_id = new TDBUniqueSearch('medico_id','db_clinica', 'Medicos', 'id', 'nome');
        $paciente_id = new TDBUniqueSearch('paciente_id','db_clinica', 'Pacientes', 'id', 'nome');

        $this->form->addFields([ new TLabel('Id')], [$id]);
        $this->form->addFields([ new TLabel('Data da Consulta')], [$data_consulta]);
        $this->form->addFields([ new TLabel('Especialidade ID')], [$especialidade_id]);
        $this->form->addFields([ new TLabel('Horário ID')], [$horario_id]);
        $this->form->addFields([ new TLabel('Médico ID')], [$medico_id]);
        $this->form->addFields([ new TLabel('Paciente ID')], [$paciente_id]);

        //$data_inscricao->setExitAction($exit_action);

        $data_consulta->setMask('dd/mm/yyyy');
        $data_consulta->setDatabaseMask('yyyy-mm-dd');

        //$uf->addValidation('Uf', new TRequiredValidator);
       // $data_consulta->addValidation('Data da Consulta', new TRequiredValidator);
       // $especialidade_id->addValidation('Especialidade ID', new TRequiredValidator);
       // $horario_id->addValidation('Horário ID', new TRequiredValidator);
       // $medico_id->addValidation('Médico ID', new TRequiredValidator);
        //$paciente_id->addValidation('Paciente ID', new TRequiredValidator);

        $id->setSize('100%');
        $data_consulta->setSize('100%');
        $especialidade_id->setSize('100%');
        $horario_id->setSize('100%');
        $medico_id->setSize('100%');
        $paciente_id->setSize('100%');

        $id->setEditable(FALSE);

        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save' );
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction([$this, 'onEdit']), 'fa:eraser red');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        parent::add($container);

    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}