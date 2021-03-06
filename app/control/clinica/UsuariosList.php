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
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TDropDown;
use Adianti\Wrapper\BootstrapDatagridWrapper;
use Adianti\Wrapper\BootstrapFormBuilder;

class UsuariosList extends TPage
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
        $this->setActiveRecord('Usuarios');
        $this->setDefaultOrder('id', 'asc');
        $this->setLimit(10);

        $this->addFilterField('id', '=','id');
        $this->addFilterField('ativo','like', 'ativo');
        $this->addFilterField('email','like', 'email');
        $this->addFilterField('senha','like', 'senha');
        $this->addFilterField('codigo_verificador','like', 'codigo_verificador');
        
        $this->form = new BootstrapFormBuilder('form_search_Usuarios');
        $this->form->setFormTitle('Usuarios');

        $id = new TEntry('id');
        $ativo = new TEntry('ativo');
        $email = new TEntry('email');
        $senha = new TEntry('senha');
        $codigo_verificador = new TEntry('codigo_verificador');

        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel('Ativo')], [$ativo]);
        $this->form->addFields([new TLabel('Email')], [$email]);
        $this->form->addFields([new TLabel('Senha')], [$senha]);
        $this->form->addFields([new TLabel('Codigo Verificador')], [$codigo_verificador]);

        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data_'));

        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['UsuariosForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');

        //Cria datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width 100%';

        //cria as colunas
        $column_id = new TDataGridColumn('id', 'Id', 'center', '10%');
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_senha = new TDataGridColumn('senha', 'Senha', 'left');
        $column_codigo_verificador = new TDataGridColumn('codigo_verificador', 'Codigo_verificador', 'left');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_senha);
        $this->datagrid->addColumn($column_codigo_verificador);

        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_ativo->setAction(new TAction([$this, 'onReload']), ['order' => 'ativo']);
        $column_email->setAction(new TAction([$this, 'onReload']), ['order' => 'email']);
        $column_senha->setAction(new TAction([$this, 'onReload']), ['order' => 'senha']);
        $column_codigo_verificador->setAction(new TAction([$this, 'onReload']), ['order' => 'codigo_verificador']);

        $action1 = new TDataGridAction(['UsuariosForm', 'onEdit'], ['id' =>'{id}', 'register_start' =>'false']);
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