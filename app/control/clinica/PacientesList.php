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

class PacientesList extends TPage
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
        $this->setActiveRecord('Pacientes');
        $this->setDefaultOrder('id', 'asc');
        $this->setLimit(10);

        $this->addFilterField('id', '=','id');
        $this->addFilterField('data_nascimento', 'like','data_nascimento');
        $this->addFilterField('nome', 'like','nome');
        $this->addFilterField('usuario_id', 'like','usuario_id');
        
        $this->form = new BootstrapFormBuilder('form_search_Pacientes');
        $this->form->setFormTitle('Pacientes');

        $id = new TEntry('id');
        $data_nascimento = new TDate('data_inscricao');        
        $nome = new TEntry('nome');
        $usuario_id = new TEntry('usuario_id','db_clinica', 'Usuario', 'id', 'nome' );

        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel('Data de Nascimento')], [$data_nascimento]);
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $this->form->addFields([new TLabel('Usuario ID')], [$usuario_id]);

        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data_'));

        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['PacientesForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');

        //Cria datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width 100%';

        //cria as colunas
        $column_id = new TDataGridColumn('id', 'Id', 'center', '10%');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data de Nascimento', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_usuario_id = new TDataGridColumn('usuario_id', 'Usuario ID', 'left');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_usuario_id);

        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_data_nascimento->setAction(new TAction([$this, 'onReload']), ['order' => 'data_nascimento']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_usuario_id->setAction(new TAction([$this, 'onReload']), ['order' => 'usuario_id']);

        $column_data_nascimento->setTransformer(function ($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $action1 = new TDataGridAction(['PacientesForm', 'onEdit'], ['id' =>'{id}', 'register_start' =>'false']);
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