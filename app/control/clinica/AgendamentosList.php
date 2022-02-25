<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Registry\TSession;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TDropDown;
use Adianti\Wrapper\BootstrapDatagridWrapper;
use Adianti\Wrapper\BootstrapFormBuilder;

class AgendamentosList extends TPage
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;

    use \Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('db_clinica');
        $this->setActiveRecord('Agendamentos');
        $this->setDefaultOrder('id', 'asc');
        $this->setLimit(10);

        $this->addFilterField('id', '=','id');
        $this->addFilterField('data_consulta', 'like','data_consulta');
        $this->addFilterField('especialidade_id', 'like','especialidade_id');
        $this->addFilterField('horario_id', 'like','horario_id');
        $this->addFilterField('medico_id', 'like','medico_id');
        $this->addFilterField('paciente_id', 'like','paciente_id');
        
        $this->form = new BootstrapFormBuilder('form_search_Agendamentos');
        $this->form->setFormTitle('Agendamentos');

        $id = new TEntry('id');
        $data_consulta = new TDate('data_consulta');
        $especialidade_id = new TDBCombo('especialidade_id','db_clinica', 'Especialidades', 'id', 'titulo');
        $horario_id = new TDBCombo('horario_id','db_clinica', 'Horas', 'hora_minuto', 'hora_minuto');
        $medico_id = new TDBUniqueSearch('medico_id','db_clinica', 'Medicos', 'id', 'nome');
        $paciente_id = new TDBUniqueSearch('paciente_id','db_clinica', 'Pacientes', 'id', 'nome');
        
        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel('Data de Consulta')], [$data_consulta]);
        $this->form->addFields([new TLabel('Especialidade')], [$especialidade_id]);
        $this->form->addFields([new TLabel('Horario')], [$horario_id]);
        $this->form->addFields([new TLabel('Medico')], [$medico_id]);
        $this->form->addFields([new TLabel('Paciente')], [$paciente_id]);

        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data_'));

        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['AgendamentosForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');

        //Cria datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width 100%';

        //cria as colunas
        $column_id = new TDataGridColumn('id', 'Id', 'center', '10%');
        $column_data_consulta = new TDataGridColumn('data_consulta', 'Data da Consulta', 'left');
        $column_especialidade_id = new TDataGridColumn('{especialidades->titulo}', 'Especialidade', 'left');
        $column_horario_id = new TDataGridColumn('{horarios->hora_minuto}', 'Horário ID', 'left');
        $column_medico_id = new TDataGridColumn('{medicos->nome}', 'Médico ID', 'left');
        $column_paciente_id = new TDataGridColumn('{pacientes->nome}', 'Paciente ID', 'left');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_data_consulta);
        $this->datagrid->addColumn($column_especialidade_id);
        $this->datagrid->addColumn($column_horario_id);
        $this->datagrid->addColumn($column_medico_id);
        $this->datagrid->addColumn($column_paciente_id);

        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_data_consulta->setAction(new TAction([$this, 'onReload']), ['order' => 'data_consulta']);
        $column_especialidade_id->setAction(new TAction([$this, 'onReload']), ['order' => 'especialidade_id']);
        $column_horario_id->setAction(new TAction([$this, 'onReload']), ['order' => 'horario_id']);
        $column_medico_id->setAction(new TAction([$this, 'onReload']), ['order' => 'medico_id']);
        $column_paciente_id->setAction(new TAction([$this, 'onReload']), ['order' => 'paciente_id']);

        $column_data_consulta->setTransformer(function ($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $action1 = new TDataGridAction(['AgendamentosForm', 'onEdit'], ['id' =>'{id}', 'register_start' =>'false']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id' =>'{id}']);

        $this->datagrid->addAction($action1, _t('Edit'), 'far:edit blue');
        $this->datagrid->addAction($action2, _t('Delete'), 'far:trash-alt red');

        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']) );

        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction(_t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static' => '1']), 'fa:table blue');
        $dropdown->addAction(_t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static' => '1']), 'fa:file-pdf red');
        $panel->addHeaderWidget($dropdown);
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

        }
}