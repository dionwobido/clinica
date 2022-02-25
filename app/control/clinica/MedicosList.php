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

class MedicosList extends TPage
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
        $this->setActiveRecord('Medicos');
        $this->setDefaultOrder('id', 'asc');
        $this->setLimit(10);

        $this->addFilterField('id', '=','id');
        $this->addFilterField('crm', 'like','crm');
        $this->addFilterField('data_inscricao', 'like','data_inscricao');
        $this->addFilterField('nome', 'like','nome');
        $this->addFilterField('usuario_id', 'like','usuario_id');
        
        $this->form = new BootstrapFormBuilder('form_search_Medicos');
        $this->form->setFormTitle('Medicos');

        $id = new TEntry('id');
        $crm = new TEntry('crm');
        $data_inscricao = new TDate('data_inscricao');
        $nome = new TEntry('nome');
        $usuario_id = new TEntry('usuario_id','db_condominio', 'Usuario', 'id', 'nome' );

        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel('CRM')], [$crm]);
        $this->form->addFields([new TLabel('Data de Inscricao')], [$data_inscricao]);
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $this->form->addFields([new TLabel('Usuario ID')], [$usuario_id]);

        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data_'));

        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['MedicosForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');

        //Cria datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width 100%';

        //cria as colunas
        $column_id = new TDataGridColumn('id', 'Id', 'center', '10%');
        $column_crm = new TDataGridColumn('crm', 'CRM', 'left');
        $column_data_inscricao = new TDataGridColumn('data_inscricao', 'Data de Inscrição', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_usuario_id = new TDataGridColumn('usuario_id', 'Usuario ID', 'left');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_crm);
        $this->datagrid->addColumn($column_data_inscricao);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_usuario_id);

        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_crm->setAction(new TAction([$this, 'onReload']), ['order' => 'crm']);
        $column_data_inscricao->setAction(new TAction([$this, 'onReload']), ['order' => 'data_inscricao']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_usuario_id->setAction(new TAction([$this, 'onReload']), ['order' => 'usuario_id']);

        $column_data_inscricao->setTransformer(function ($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $action1 = new TDataGridAction(['MedicosForm', 'onEdit'], ['id' =>'{id}', 'register_start' =>'false']);
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